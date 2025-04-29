<?php

namespace Modules\NeaMeeting\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\MeetingStatus;
use Illuminate\Http\Request;
use App\Events\MeetingCreated;
use App\Events\MeetingUpdated;
use App\Events\MeetingCancelled;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\Traits\BulkDeletableTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\InlineEditableTrait;
use Illuminate\Support\Facades\Log;
use App\Helpers\NepaliDateConverter;
use Illuminate\Support\Facades\Auth;
use Modules\NeaMeeting\Models\Meeting;
use App\Notifications\MeetingNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\BaseAdminController;
use App\Notifications\ExternalMeetingNotification;
use Modules\NeaMeeting\DataTables\MeetingDataTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\NeaMeeting\Http\Requests\StoreMeetingRequest;
use Modules\GoogleCalendar\Services\GoogleCalendarService;
use Modules\NeaMeeting\Http\Requests\UpdateMeetingRequest;

class MeetingController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;
    use InlineEditableTrait;
    
    protected $model = Meeting::class;
    protected string $resourcePermission = 'meetings';
    protected string $resourceName = 'meetings';
    protected string $formView = 'neameeting::pages.meetings.meetingForm';
    protected $googleCalendarService;
    protected $emailService;
    protected $smsService;
    
    public function __construct(ResponseService $responseService,GoogleCalendarService $googleCalendarService,$emailService = null, $smsService = null)
    {
        parent::__construct($responseService);
        $this->googleCalendarService = $googleCalendarService;
        $this->emailService = $emailService; // Injected or configured email service
        $this->smsService = $smsService; // Injected or configured SMS service
    }
    
    public function index(MeetingDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        $users=User::all();
        return $this->renderForm($this->formView,null,['users' => $users,'googleCalendarEnabled' => app(GoogleCalendarService::class)->isEnabled()]);
    }
    

    public function store(StoreMeetingRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            DB::beginTransaction();
            
            try {
                $validated=$request->validated();
                $meeting = Meeting::create($validated);
                if ($validated['is_external'] && isset($validated['external_contacts'])) {
                    foreach ($validated['external_contacts'] as $contact) {
                        $meeting->externalContacts()->create($contact);
                    }
                }
                        
                // Handle media upload if present
                $this->handleMediaUploads($request, $meeting);
                
                if ($request->hasFile('meetingDocuments')) {
                    $meeting->addMedia($request->file('meetingDocuments'))->toMediaCollection('meetingDocuments');
                }

                // Add Google Calendar integration - Only if enabled in settings
                if ($this->googleCalendarService->isEnabled() && $request->boolean('add_to_google_calendar', true)) {
                    try {
                        $googleEvent = $this->googleCalendarService->createEvent($meeting);
                        if ($googleEvent) {
                            Log::info('Google Calendar event created for meeting #' . $meeting->id);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
                    }
                }
                
                // Trigger the meeting created event
                if ($request->boolean('send_notifications', true)) {
                    // dd('test');
                    event(new MeetingCreated($meeting, [
                        'send_email' => $request->boolean('send_email', true),
                        'send_sms' => $request->boolean('send_sms', true),
                        'organization_ids' => $request->organizations ?? [],
                    ]));
                }
                

                DB::commit();
                if ($request->has('save_and_add_more')) {
                    return redirect()
                        ->route('admin.meetings.create')
                        ->with('success', 'Meeting created successfully. Add another one.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }, 'admin.meetings.index', 'Meeting created successfully.', 'Failed to create the Meeting.');
    }


    public function show(Meeting $meeting)
    {
        $meeting->load(['media' => function($query) {
            $query->where('collection_name', 'meetings');
        }]);
        return view('neameeting::pages.meetings.show', ['resource' => $meeting]);
    }
    
    public function edit(Meeting $meeting)
    {
        $meeting->load(['media' => function($query) {
            $query->where('collection_name', 'meetings');
        }, 'externalContacts']);
        return $this->renderForm($this->formView, $meeting,['googleCalendarEnabled' => app(GoogleCalendarService::class)->isEnabled(),]);
    }
    

    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        // dd($request->all());
        return $this->handleRequest($request, function () use ($request, $meeting) {
            DB::beginTransaction();
            try {
            $validated = $request->validated();

            if (empty($validated['end_time'])) {
                $validated['end_time'] = null;
            }

            // Update meeting details
            $meeting->update($validated);
            if ($validated['is_external'] && isset($validated['external_contacts'])) {
                $meeting->externalContacts()->delete();
                foreach ($validated['external_contacts'] as $contact) {
                    $meeting->externalContacts()->create($contact);
                }
             }else{
                $meeting->externalContacts()->delete();
            }
            $this->handleMediaUploads($request, $meeting);
            if ($request->hasFile('meetingDocuments')) {
                $meeting->addMedia($request->file('meetingDocuments'))->toMediaCollection('meetingDocuments');
            }

             // Update Google Calendar event if enabled
            if ($this->googleCalendarService->isEnabled() && $request->boolean('update_google_calendar', true)) {
                try {
                    $googleEvent = $this->googleCalendarService->updateEvent($meeting);
                    if ($googleEvent) {
                        Log::info('Google Calendar event updated for meeting #' . $meeting->id);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
                }
            }
            // trigger the evnts
            if ($request->boolean('send_notifications', true)) {
                // dd($validated['status']);
                if ($validated['status'] === 'Cancelled') {
                    event(new MeetingCancelled($meeting, [
                        'send_email' => $request->boolean('send_email', true),
                        'send_sms' => $request->boolean('send_sms', true),
                        'reason' => $request->input('cancellation_reason', ''),
                        'organization_ids' => $request->organizations ?? [],
                    ]));
                } else {
                    // dd('test');
                    event(new MeetingUpdated($meeting, [
                        'send_email' => $request->boolean('send_email', true),
                        'send_sms' => $request->boolean('send_sms', true),
                        'organization_ids' => $request->organizations ?? [],
                    ]));
                }
            }
            
             DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }, 'admin.meetings.index', 'Meeting updated successfully.', 'Failed to update the Meeting.');
    }
    public function destroy(Request $request, Meeting $meeting)
    {
        return $this->handleRequest($request, function () use ($meeting) {
            $meeting->delete();
        }, 'admin.meetings.index', 'Meeting deleted successfully.', 'Failed to delete the Meeting.');
    }
    private function handleMediaUploads(Request $request, Meeting $meeting)
    {
        // Handle media deletion requests
        if ($request->has('meetingDocuments')) {
            foreach ($request->input('meetingDocuments', []) as $mediaId) {
                $media = Media::find($mediaId);
                if ($media && $media->model_id == $meeting->id) {
                    $media->delete();
                }
            }
        }

        // Add new media uploads
        if ($request->has('meetingDocuments')) {
            foreach ($request->input('meetingDocuments', []) as $fileName) {
                $filePath = storage_path('app/temp/dropzone/' . $fileName);
                if (file_exists($filePath)) {
                    $meeting->addMedia($filePath)->toMediaCollection('meetings');
                }
            }
        }
    }

    public function getByDate($year, $month, $day)
    {
        $converter = new NepaliDateConverter();
        $adDate = $converter->toGregorianDate($year, $month, $day);
        $formattedDate=$adDate['gregorian_date'];
        $user=Auth::user();
        $today = Carbon::today()->toDateString(); 

        $meetings = DB::table('meetings')
        ->whereDate('meeting_date_ad', '=', $formattedDate)
        ->when(!$user->hasAnyRole(['admin', 'superadmin']), function ($query) use ($user) {
            return $query->whereJsonContains('meetings.organizations', (string) $user->organization_id);
        })
        ->orderBy('start_time')
        ->get();

        // dd($meetings);
 
        return response()->json([
            'success' => true,
            'meetings' => $meetings,
            'date' => [
                'nepali' => [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'formatted' => $year . '-' . $month . '-' . $day
                ],
                'ad' => [
                    'year' => $adDate['year'],
                    'month' => $adDate['month'],
                    'day' => $adDate['day'],
                    'formatted' => $formattedDate
                ]
            ]
        ]);
    }
    
    /**
     * Get dates that have meetings in a specific month
     */
    public function getMeetingDates($year, $month)
    {
     
        $converter = new NepaliDateConverter();
        // Get first day of the month
        $firstDayAd = $converter->toGregorianDate($year, $month, 1);
        $firstDay = Carbon::create($firstDayAd['year'], $firstDayAd['month'], $firstDayAd['day']);
        // Get days in the nepali month
        $daysInMonth = $converter->getNumberOfDaysInMonth($year, $month);
        
        // Get last day of the month
        $lastDayAd = $converter->toGregorianDate($year, $month, $daysInMonth);
        $lastDay = Carbon::create($lastDayAd['year'], $lastDayAd['month'], $lastDayAd['day']);
        
        // Get all meetings in this date range
        $meetings = Meeting::whereBetween('meeting_date_ad', [$today->format('Y-m-d'), $lastDay->format('Y-m-d')])
        ->orderBy('meeting_date_ad')
        ->get();
        
        // Group dates that have meetings
        $meetingDates = [];
        
        foreach ($meetings as $meeting) {
            $adDate = Carbon::parse($meeting->meeting_date_ad);
            $bsDate = $converter->toNepaliDate($adDate->year, $adDate->month, $adDate->day);
            
            $dateKey = $bsDate['year'] . '-' . $bsDate['month'] . '-' . $bsDate['day'];
            
            if (!in_array($dateKey, $meetingDates)) {
                $meetingDates[] = $dateKey;
            }
        }
        
        return response()->json($meetingDates);
    }
    public function checkConflict(Request $request)
    {
        $request->validate(['meeting_date' => 'required|date','start_time' => 'required']);
    
        $meetingDate = $request->meeting_date;
        $startTime = $request->start_time;
    
        // Convert Nepali date to AD date
       $date=explode('-',$meetingDate);
       $year=$date[0];
       $month=$date[1];
       $day=$date[2];
       $converter = new NepaliDateConverter();
       $adDate = $converter->toGregorianDate($year, $month, $day);
       $meetingDateAd = $adDate['gregorian_date'];

       $startTime = Carbon::createFromFormat('h:i A', $startTime)->format('H:i:s');
       $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$meetingDateAd $startTime");
       $conflictingMeeting = Meeting::where('start_time', $startDateTime)->where('status', '!=', 'cancelled')->first();
    
        return response()->json([
            'conflict' => !is_null($conflictingMeeting),
            'meeting' => $conflictingMeeting ? [
                'title' => $conflictingMeeting->title,
                'id' => $conflictingMeeting->id,
                'start_time' => $conflictingMeeting->start_time,
                'end_time' => $conflictingMeeting->end_time
            ] : null
        ]);
    }
    public function checkTimeValidation(Request $request)
    {
        $request->validate([
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
    
        $meetingDate = $request->meeting_date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
    
        // Convert Nepali date to AD date
        $date = explode('-', $meetingDate);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $converter = new NepaliDateConverter();
        $adDate = $converter->toGregorianDate($year, $month, $day);
        $meetingDateAd = $adDate['gregorian_date'];
    
        // Validate meeting date is today or future
        $meetingDateCarbon = Carbon::createFromFormat('Y-m-d', $meetingDateAd);
        if ($meetingDateCarbon->lt(Carbon::today())) {
            return response()->json([
                'error' => 'Meeting date must be today or in the future'
            ], 422);
        }
    
        $startTime = Carbon::createFromFormat('h:i A', $startTime)->format('H:i:s');
        $endTime = Carbon::createFromFormat('h:i A', $endTime)->format('H:i:s');
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$meetingDateAd $startTime");
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$meetingDateAd $endTime");
    
        // Validate end time is after start time
        if ($endDateTime->lte($startDateTime)) {
            return response()->json([
                'error' => 'End time must be after start time'
            ], 422);
        }
    
        $conflictingMeeting = Meeting::where('start_time', $startDateTime)->where('status', '!=', 'cancelled')->first();
    
        return response()->json([
            'conflict' => !is_null($conflictingMeeting),
            'meeting' => $conflictingMeeting ? [
                'title' => $conflictingMeeting->title,
                'id' => $conflictingMeeting->id,
                'start_time' => $conflictingMeeting->start_time,
                'end_time' => $conflictingMeeting->end_time
            ] : null
        ]);
    }
    public function cancel(Request $request, $id)
    {
        // Find the meeting
        $meeting = Meeting::find($id);
    
        if (!$meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Meeting not found.'
            ], 404);
        }
    
        // Check if the user has the 'md' role
        if (!Auth::user()->hasRole('md')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }
    
        // Check if the meeting is already cancelled
        if ($meeting->status === 'Cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Meeting is already cancelled.'
            ], 400);
        }
    
        // Update the meeting status to Cancelled
        $meeting->status = 'Cancelled';
        $meeting->save();
    
        if ($this->googleCalendarService->isEnabled() && !empty($meeting->google_calendar_event_id)) {
            try {
                $this->googleCalendarService->updateEvent($meeting);
                Log::info('Google Calendar event updated for cancelled meeting #' . $meeting->id);
            } catch (\Exception $e) {
                Log::error('Failed to update Google Calendar event for cancelled meeting: ' . $e->getMessage());
            }
        }
    
        event(new MeetingCancelled($meeting, [
            'send_email' => $request->boolean('send_email', true),
            'reason' => $request->input('cancellation_reason', ''), // Optional reason
            'organization_ids' => $request->input('organizations', []),
        ]));
    
        return response()->json([
            'success' => true,
            'message' => 'Meeting cancelled successfully.'
        ]);
    }
    public function sendNotifications(Request $request, Meeting $meeting)
    {
        try {
            // Default notification options (can be overridden by request input if needed)
            $sendEmail = $request->input('send_email', true);
            $sendSms = $request->input('send_sms', false);

            if (!$sendEmail && !$sendSms) {
                return redirect()->back()->with('warning', 'No notifications sent: both email and SMS are disabled.');
            }

            // Check if required services are active and available
            if ($sendEmail && (!$this->emailService || !method_exists($this->emailService, 'isActive') || !$this->emailService->isActive())) {
                Log::warning('Email service is not active or unavailable. Skipping email notifications.');
                if (!$sendSms) {
                    return redirect()->back()->with('warning', 'No notifications sent: email service is unavailable and SMS is disabled.');
                }
                $sendEmail = false;
            }

            if ($sendSms && (!$this->smsService || !method_exists($this->smsService, 'isActive') || !$this->smsService->isActive())) {
                Log::warning('SMS service is not active or unavailable. Skipping SMS notifications.');
                if (!$sendEmail) {
                    return redirect()->back()->with('warning', 'No notifications sent: SMS service is unavailable and email is disabled.');
                }
                $sendSms = false;
            }

            // Get organization IDs from the meeting's JSON column
            $organizationIds = json_decode($meeting->organizations, true) ?? [];
            $users = !empty($organizationIds) ? User::whereIn('organization_id', $organizationIds)->get() : collect();
            $externalContacts = $meeting->externalContacts ?? collect();

            if ($users->isEmpty() && $externalContacts->isEmpty()) {
                return redirect()->back()->with('warning', 'No users or external contacts found to notify.');
            }

            // Send notifications to users
            $notifiedUserIds = [];
            $smsUserIds = [];

            foreach ($users as $user) {
                // Send email notification if enabled
                if ($sendEmail) {
                    try {
                        $user->notify(new MeetingNotification($meeting));
                        $notifiedUserIds[] = $user->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting email notification for user #' . $user->id . ': ' . $e->getMessage());
                    }
                }

                // Send SMS notification if enabled and user has mobile_no
                if ($sendSms && !empty($user->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($meeting, $user);
                        $result = $this->smsService->send($user->mobile_no, $message);

                        if ($result['success']) {
                            $smsUserIds[] = $user->id;
                        } else {
                            Log::error('Failed to send meeting SMS for user #' . $user->id . ': ' . $result['message']);
                        }
                    } catch (\Exception $e) {
                        Log::error('SMS service error for user #' . $user->id . ': ' . $e->getMessage());
                    }
                }
            }

            // Store notified users for email
            if (!empty($notifiedUserIds)) {
                $meeting->notifiedUsers()->create([
                    'users' => json_encode($notifiedUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            }

            // Store notified users for SMS
            if (!empty($smsUserIds)) {
                $meeting->notifiedUsers()->create([
                    'users' => json_encode($smsUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms',
                    'notification_status' => 'sent',
                ]);
            }

            // Send notifications to external contacts
            $notifiedExternalIds = [];
            $smsExternalIds = [];

            foreach ($externalContacts as $contact) {
                // Send email notification if enabled
                if ($sendEmail) {
                    try {
                        if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                            Log::warning('Invalid email for external contact #' . $contact->id . ': ' . $contact->email);
                            continue;
                        }
                        $contact->notify(new ExternalMeetingNotification($meeting));
                        $notifiedExternalIds[] = $contact->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting notification to external contact #' . $contact->id . ': ' . $e->getMessage());
                    }
                }

                // Send SMS notification if enabled and contact has mobile_no
                if ($sendSms && !empty($contact->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($meeting, $contact, true);
                        $result = $this->smsService->send($contact->mobile_no, $message);

                        if ($result['success']) {
                            $smsExternalIds[] = $contact->id;
                        } else {
                            Log::error('Failed to send meeting SMS for external contact #' . $contact->id . ': ' . $result['message']);
                        }
                    } catch (\Exception $e) {
                        Log::error('SMS service error for external contact #' . $contact->id . ': ' . $e->getMessage());
                    }
                }
            }

            // Store notified external contacts for email
            if (!empty($notifiedExternalIds)) {
                $meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($notifiedExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            }

            // Store notified external contacts for SMS
            if (!empty($smsExternalIds)) {
                $meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($smsExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms',
                    'notification_status' => 'sent',
                ]);
            }

            return redirect()->back()->with('success', 'Notifications sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error sending meeting notifications: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send notifications. Please try again.');
        }
    }

    
}