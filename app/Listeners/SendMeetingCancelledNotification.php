<?php

namespace App\Listeners;

use Log;
use App\Models\User;
use App\Events\MeetingCancelled;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\MeetingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Models\EmailConfiguration;
use App\Notifications\ExternalMeetingNotification;
use Modules\Settings\Services\DynamicEmailService;
use App\Notifications\MeetingCancelledNotification;
use App\Notifications\MeetingCancellationNotification;
use Modules\Settings\Services\Sms\SmsServiceInterface;
use App\Notifications\ExternalMeetingCancelledNotification;
use App\Notifications\ExternalMeetingCancellationNotification;

class SendMeetingCancelledNotification
{

    protected $emailService;
    protected $smsService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->emailService = app(DynamicEmailService::class);
        $this->smsService = app(SmsServiceInterface::class);
    }

    /**
     * Handle the event.
     */
    public function handle(MeetingCancelled $event)
    {
        // dd($event);
        // Check if notifications should be sent
        $sendEmail = $event->options['send_email'] ?? true;
        $sendSms = $event->options['send_sms'] ?? false;

        if (!$sendEmail && !$sendSms) {
            return;
        }


        $emailActive = EmailConfiguration::where('is_active', true)->exists();
        $smsActive = SmsConfiguration::where('is_active', true)->exists();

        if (!$emailActive && !$smsActive) {
            $this->error('Both email and SMS services are inactive. Cannot send any notifications.');
            return 1;
        }
        

        // Get organization IDs from the event options or meeting's JSON column
        $organizationIds = $event->options['organization_ids'] ?? json_decode($event->meeting->organizations, true) ?? [];
        $users = !empty($organizationIds) ? User::whereIn('organization_id', $organizationIds)->get() : collect();
        $externalContacts = $event->meeting->externalContacts ?? collect();

        // dd($users);

        if ($users->isEmpty() && $externalContacts->isEmpty()) {
            return;
        }

        // Send notifications to users
        try {
            $notifiedUserIds = [];
            $smsUserIds = [];

            foreach ($users as $user) {
                // dd($user);
                // Send email notification if enabled
                if ($emailActive) {
                    try {
                        $user->notify(new MeetingCancellationNotification($event->meeting, $event->options['reason'] ?? ''));
                        $notifiedUserIds[] = $user->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting cancellation email notification for user #' . $user->id . ': ' . $e->getMessage());
                    }
                }

                // Send SMS notification if enabled and user has mobile_no
                if ($smsActive && !empty($user->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($event->meeting, $user, $event->options['reason'] ?? '');
                        $result = $this->smsService->send($user->mobile_no, $message);

                        if ($result['success']) {
                            $smsUserIds[] = $user->id;
                        } else {
                            Log::error('Failed to send meeting cancellation SMS for user #' . $user->id . ': ' . $result['message']);
                        }
                    } catch (\Exception $e) {
                        Log::error('SMS service error for user #' . $user->id . ': ' . $e->getMessage());
                    }
                }
            }

            // Store the notified users in a single record for email
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
            Log::error('Failed to save meeting cancellation notification record: ' . $e->getMessage());
        }

        // Send notifications to external contacts
        try {
            $notifiedExternalIds = [];
            $smsExternalIds = [];

            foreach ($externalContacts as $contact) {
                // Send email notification if enabled
                if ($emailActive) {
                    try {
                        if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                            Log::warning('Invalid email for external contact #' . $contact->id . ': ' . $contact->email);
                            continue;
                        }
                        $contact->notify(new ExternalMeetingCancellationNotification($event->meeting, $event->options['reason'] ?? ''));
                        $notifiedExternalIds[] = $contact->id;
                    } catch (\Exception $e) {
                        Log::error('Failed to send meeting cancellation notification to external contact #' . $contact->id . ': ' . $e->getMessage());
                    }
                }

                // Send SMS notification if enabled and contact has mobile_no
                if ($smsActive && !empty($contact->mobile_no)) {
                    try {
                        $message = $this->formatSmsMessage($event->meeting, $contact, $event->options['reason'] ?? '', true);
                        $result = $this->smsService->send($contact->mobile_no, $message);

                        if ($result['success']) {
                            $smsExternalIds[] = $contact->id;
                        } else {
                            Log::error('Failed to send meeting cancellation SMS for external contact #' . $contact->id . ': ' . $result['message']);
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
            Log::error('Failed to save meeting cancellation notification record for external contacts: ' . $e->getMessage());
        }
    }

    /**
     * Format the SMS message with meeting cancellation details
     *
     * @param Meeting $meeting
     * @param User|ExternalContact $recipient
     * @param string $reason
     * @param bool $isExternal
     * @return string
     */
    protected function formatSmsMessage($meeting, $recipient, $reason = '', $isExternal = false)
    {
        $name = $isExternal ? $recipient->name : $recipient->name;
        $appName = config('app.name');
        $meetingTitle = $meeting->title;
        $date = $meeting->scheduled_at;
        $time = $meeting->scheduled_at;
        $reasonText = !empty($reason) ? " Reason: {$reason}" : '';

        return "Hi {$name}, the meeting \"{$meetingTitle}\" scheduled on {$date} at {$time} has been cancelled.{$reasonText} Please check your email for details. - {$appName}";
    }
}