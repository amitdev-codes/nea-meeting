<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\User;
use App\Events\MeetingEvent;
use Illuminate\Support\Facades\Log;
use App\Helpers\NepaliDateConverter;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Models\EmailConfiguration;
use App\Notifications\MeetingStatusNotification;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\Services\Sms\SmsServiceInterface;

class MeetingNotificationListener
{
    protected $emailService;
    protected $smsService;

    public function __construct()
    {
        $this->emailService = app(DynamicEmailService::class);
        $this->smsService = app(SmsServiceInterface::class);
    }

    public function handle(MeetingEvent $event): void
    {
        $options = $event->options ?? [];
        $sendEmail = $options['send_email'] ?? true;
        $sendSms = $options['send_sms'] ?? false;

        Log::info('Handling MeetingEvent', [
            'meeting_id' => $event->meeting->id,
            'notification_type' => $event->notificationType,
            'send_email' => $sendEmail,
            'send_sms' => $sendSms,
            'options' => $options,
        ]);

        if (!$sendEmail && !$sendSms) {
            Log::info('No notifications to send (both email and SMS disabled).');
            return;
        }

        $emailActive = EmailConfiguration::where('is_active', true)->exists();
        $smsActive = SmsConfiguration::where('is_active', true)->exists();

        if (!$emailActive && !$smsActive) {
            Log::error('Both email and SMS services are inactive. Cannot send notifications.');
            return;
        }

        $organizationIds = $options['organization_ids'] ?? json_decode($event->meeting->organizations ?? '[]', true);
        Log::info('Organization IDs for notification', ['organization_ids' => $organizationIds]);

        $users = !empty($organizationIds)
            ? User::whereIn('organization_id', $organizationIds)->get()
            : collect();
        $externalContacts = $event->meeting->externalContacts ?? collect();

        Log::info('Notification recipients', [
            'user_count' => $users->count(),
            'external_contact_count' => $externalContacts->count(),
        ]);

        if ($users->isEmpty() && $externalContacts->isEmpty()) {
            Log::warning('No users or external contacts found for notifications.');
            return;
        }

        $this->sendNotifications($event, $users, $externalContacts, $sendEmail, $sendSms, $emailActive, $smsActive);
    }

    private function sendNotifications(
        MeetingEvent $event,
        $users,
        $externalContacts,
        bool $sendEmail,
        bool $sendSms,
        bool $emailActive,
        bool $smsActive
    ): void {
        $this->sendUserNotifications($event, $users, $sendEmail, $sendSms, $emailActive, $smsActive);
        $this->sendExternalContactNotifications($event, $externalContacts, $sendEmail, $sendSms, $emailActive, $smsActive);
    }

    private function sendUserNotifications(
        MeetingEvent $event,
        $users,
        bool $sendEmail,
        bool $sendSms,
        bool $emailActive,
        bool $smsActive
    ): void {
        $notifiedUserIds = [];
        $smsUserIds = [];

        foreach ($users as $user) {
            if ($sendEmail && $emailActive && !empty($user->email)) {
                try {
                    $user->notify(new MeetingStatusNotification($event->meeting, $event->notificationType, false));
                    $notifiedUserIds[] = $user->id;
                    Log::info("Sent {$event->notificationType} email to user #{$user->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send {$event->notificationType} email to user #{$user->id}: {$e->getMessage()}");
                }
            }

            if ($sendSms && $smsActive && !empty($user->mobile_no)) {
                try {
                    $message = $this->formatSmsMessage($event->meeting, $user, $event->notificationType, false, $event->options);
                    $result = $this->smsService->send($user->mobile_no, $message);

                    if ($result['success']) {
                        $smsUserIds[] = $user->id;
                        Log::info("Sent {$event->notificationType} SMS to user #{$user->id}");
                    } else {
                        Log::error("Failed to send {$event->notificationType} SMS to user #{$user->id}: {$result['message']}");
                    }
                } catch (\Exception $e) {
                    Log::error("SMS service error for user #{$user->id}: {$e->getMessage()}");
                }
            }
        }

        $this->storeNotificationRecords($event->meeting, 'users', $notifiedUserIds, "email_{$event->notificationType}");
        $this->storeNotificationRecords($event->meeting, 'users', $smsUserIds, "sms_{$event->notificationType}");
    }

    private function sendExternalContactNotifications(
        MeetingEvent $event,
        $externalContacts,
        bool $sendEmail,
        bool $sendSms,
        bool $emailActive,
        bool $smsActive
    ): void {
        $notifiedExternalIds = [];
        $smsExternalIds = [];

        foreach ($externalContacts as $contact) {
            if ($sendEmail && $emailActive && !empty($contact->email)) {
                if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning("Invalid email for external contact #{$contact->id}: {$contact->email}");
                    continue;
                }

                try {
                    $contact->notify(new MeetingStatusNotification($event->meeting, $event->notificationType, true));
                    $notifiedExternalIds[] = $contact->id;
                    Log::info("Sent {$event->notificationType} email to external contact #{$contact->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send {$event->notificationType} email to external contact #{$contact->id}: {$e->getMessage()}");
                }
            }

            if ($sendSms && $smsActive && !empty($contact->mobile)) {
                try {
                    $message = $this->formatSmsMessage($event->meeting, $contact, $event->notificationType, true, $event->options);
                    $result = $this->smsService->send($contact->mobile, $message);

                    if ($result['success']) {
                        $smsExternalIds[] = $contact->id;
                        Log::info("Sent {$event->notificationType} SMS to external contact #{$contact->id}");
                    } else {
                        Log::error("Failed to send {$event->notificationType} SMS to external contact #{$contact->id}: {$result['message']}");
                    }
                } catch (\Exception $e) {
                    Log::error("SMS service error for external contact #{$contact->id}: {$e->getMessage()}");
                }
            }
        }

        $this->storeNotificationRecords($event->meeting, 'external_contacts', $notifiedExternalIds, "email_{$event->notificationType}");
        $this->storeNotificationRecords($event->meeting, 'external_contacts', $smsExternalIds, "sms_{$event->notificationType}");
    }

    private function formatSmsMessage($meeting, $recipient, string $notificationType, bool $isExternal, array $options): string
    {
        $name =  'Sir/Madam';
        $appName = config('app.name');
        $meetingTitle = $meeting->title;

        try {
            $meetingDate = Carbon::parse($meeting->meeting_date_ad);
            $startTime = Carbon::parse($meeting->start_time);

            $nepaliDate = NepaliDateConverter::toNepaliDate($meetingDate); 

            $nepaliDay = $nepaliDate['day'];
            $nepaliYear = $nepaliDate['year'];
            $nepaliFormattedDate = "{$nepaliDate['english_month_name']} {$nepaliDay}, {$nepaliYear}";

            $hour = $startTime->format('g:i');
            $period = $startTime->format('A') === 'AM' ? 'AM' : 'PM';
            $formattedTime = "{$hour} {$period}";

            $formattedDateTime = "{$nepaliFormattedDate}, {$formattedTime}";
        } catch (\Exception $e) {
            Log::warning("Invalid date or time for meeting #{$meeting->id}: {$e->getMessage()}");
            $formattedDateTime = 'at a scheduled date and time';
        }

        switch ($notificationType) {
            case 'scheduled':
                return "Dear {$name}, you are cordially invited to attend \"{$meetingTitle}\" scheduled for {$formattedDateTime} at {$meeting->meeting_location}.";
            case 'reminder':
                return "Reminder: Dear {$name}, this is a reminder for \"{$meetingTitle}\" on {$formattedDateTime} at {$meeting->meeting_location}.";
            case 'rescheduled':
                return "Dear {$name}, the \"{$meetingTitle}\" has been rescheduled to {$formattedDateTime} at {$meeting->meeting_location}. We apologize for any inconvenience.";
            case 'cancellation':
                $reason = isset($options['reason']) ? " Reason: {$options['reason']}" : '';
                return "Dear {$name}, the \"{$meetingTitle}\" scheduled for {$formattedDateTime} has been cancelled.Sorry for the inconvenience.";
            default:
                return "Dear {$name}, update for meeting \"{$meetingTitle}\" on {$formattedDateTime}. - {$appName}.";
        }
    }

    private function storeNotificationRecords($meeting, string $type, array $ids, string $notificationType): void
    {
        if (empty($ids)) {
            return;
        }

        try {
            $method = $type === 'users' ? 'notifiedUsers' : 'notifiedExternalContacts';
            $meeting->$method()->create([
                $type => json_encode($ids),
                'notified_at' => now(),
                'notification_type' => $notificationType,
                'notification_status' => 'sent',
            ]);
            Log::info("Stored notification records for {$notificationType} ({$type})", ['ids' => $ids]);
        } catch (\Exception $e) {
            Log::error("Failed to save {$notificationType} notification record for {$type}: {$e->getMessage()}");
        }
    }
}