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
use App\Http\Controllers\BaseAdminController;
use Modules\NeaMeeting\DataTables\MeetingDataTable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\NeaMeeting\Http\Requests\StoreMeetingRequest;
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
    
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }
    
    public function index(MeetingDataTable $dataTable)
    {
        return $dataTable->render('pages.resources.index');
    }
    
    public function create(Request $request)
    {
        $users=User::all();
        return $this->renderForm($this->formView,null,['users' => $users]);
    }
    

    public function store(StoreMeetingRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            DB::beginTransaction();
            
            try {
                $validated=$request->validated();
                // Create the meeting with validated data
                $meeting = Meeting::create($validated);
                     // Handle external contact if is_external is true

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
                
                // Trigger the meeting created event
                // if ($request->boolean('send_notifications', true)) {
                //     event(new MeetingCreated($meeting, [
                //         'send_email' => $request->boolean('send_email', true),
                //         'organization_ids' => $request->organizations ?? [],
                //     ]));
                // }

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
        return $this->renderForm($this->formView, $meeting);
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
            // trigger the evnts
            // if ($request->boolean('send_notifications', true)) {
            //     event(new MeetingUpdated($meeting, [
            //         'send_email' => $request->boolean('send_email', true),
            //         'organization_ids' => $request->organizations ?? [],
            //     ]));
            // }

            if ($request->boolean('send_notifications', true)) {
                // dd($validated['status']);
                if ($validated['status'] === 'Cancelled') {
                    event(new MeetingCancelled($meeting, [
                        'send_email' => $request->boolean('send_email', true),
                        'reason' => $request->input('cancellation_reason', ''), // Optional reason
                        'organization_ids' => $request->organizations ?? [],
                    ]));
                } else {
                    // event(new \App\Events\MeetingUpdated($meeting, [
                    //     'send_email' => $request->boolean('send_email', true),
                    //     'organization_ids' => $request->organizations ?? [],
                    // ]));
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
        $request->validate([
            'meeting_date' => 'required|date',
            'start_time' => 'required',
        ]);
    
        $meetingDate = $request->meeting_date;
        $startTime = $request->start_time;
        $meetingId = $request->meeting_id ?? 0;
        
        // Query to find conflicting meetings (including those that overlap)
        $conflictingMeeting = Meeting::where('meeting_date', $meetingDate)
            ->where(function($query) use ($startTime) {
                $query->where('start_time', $startTime)
                      ->orWhere('end_time', '>', $startTime);
            })
            ->when($meetingId > 0, function($query) use ($meetingId) {
                $query->where('id', '!=', $meetingId);
            })
            ->first();
        
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
    public function cancel($id)
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

        event(new MeetingCancelled($meeting, [
            'send_email' => $request->boolean('send_email', true),
            'reason' => $request->input('cancellation_reason', ''), // Optional reason
            'organization_ids' => $request->organizations ?? [],
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Meeting cancelled successfully.'
        ]);
    }
    
}