<?php

namespace App\Listeners;

use Log;
use App\Models\User;
use App\Events\MeetingUpdated;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\MeetingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Models\EmailConfiguration;
use App\Notifications\ExternalMeetingNotification;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\Services\Sms\SmsServiceInterface;

class SendMeetingUpdateNotification
{

    protected $emailService;
    protected $smsService;
    public function __construct()
    {
        $this->emailService = app(DynamicEmailService::class);
        $this->smsService = app(SmsServiceInterface::class);
    }

    /**
     * Handle the event.
     */
    public function handle(MeetingUpdated $event)
    {
        $sendEmail = $event->options['send_email'] ?? true;
        $sendSms = $event->options['send_sms'] ?? false;
        if (!$sendEmail && !$sendSms) {
            return;
        }

                // Check if notifications services are active
                $emailActive = EmailConfiguration::where('is_active', true)->exists();
                $smsActive = SmsConfiguration::where('is_active', true)->exists();

                if (!$emailActive && !$smsActive) {
                    $this->error('Both email and SMS services are inactive. Cannot send any notifications.');
                    return 1;
                }
        
                // dd($e

        // Get organization IDs from the meeting's JSON column or options
        $organizationIds = $event->options['organization_ids'] ?? json_decode($event->meeting->organizations, true) ?? [];
        $users = !empty($organizationIds) ? User::whereIn('organization_id', $organizationIds)->get() : collect();
        $externalContacts = $event->meeting->externalContacts ?? collect();

        if ($users->isEmpty() && $externalContacts->isEmpty()) {
            return;
        }

        // Send notifications to users
        try {
            $notifiedUserIds = [];
            $smsUserIds = [];

            foreach ($users as $user) {
                // Send email notification if enabled
                if ($sendEmail) {
                    try {
                        // dd('tests');
                        $user->notify(new MeetingNotification($event->meeting));
                        $notifiedUserIds[] = $user->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting email notification for user #' . $user->id . ': ' . $e->getMessage());
                    }
                }
                if ($smsActive && !empty($user->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($event->meeting, $user);
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

            // Store the notified users in a single record
            if (!empty($notifiedUserIds)) {
                $event->meeting->notifiedUsers()->create([
                    'users' => json_encode($notifiedUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            }
            // Store the notified users in a single record for SMS
            if (!empty($smsUserIds)) {
                $event->meeting->notifiedUsers()->create([
                    'users' => json_encode($smsUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms',
                    'notification_status' => 'sent',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to save meeting update notification record: ' . $e->getMessage());
        }

        // Send notifications to external contacts
        try {
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
                        $contact->notify(new ExternalMeetingNotification($event->meeting));
                        $notifiedExternalIds[] = $contact->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting notification to external contact #' . $contact->id . ': ' . $e->getMessage());
                    }
                }
                
                // Send SMS notification if enabled and contact has mobile_no number
                if ($sendSms && !empty($contact->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($event->meeting, $contact, true);
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
                $event->meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($notifiedExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            }
            
            // Store notified external contacts for SMS
            if (!empty($smsExternalIds)) {
                $event->meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($smsExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms',
                    'notification_status' => 'sent',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to save meeting update notification record for external contacts: ' . $e->getMessage());
        }
    }
    protected function formatSmsMessage($meeting, $recipient, $isExternal = false)
    {
        $name = $isExternal ? $recipient->name : $recipient->name;
        $appName = config('app.name');
        $meetingTitle = $meeting->title;
        $date = $meeting->scheduled_at;
        $time = $meeting->scheduled_at;
        return "Hi {$name}, a new meeting \"{$meetingTitle}\" has been scheduled on {$date} at {$time}. Please check your email for details. - {$appName}";
    }
}