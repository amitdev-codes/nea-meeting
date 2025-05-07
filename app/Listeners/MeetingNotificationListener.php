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

        if (!$sendEmail && !$sendSms) {
            return;
        }

        $emailActive = EmailConfiguration::where('is_active', true)->exists();
        $smsActive = SmsConfiguration::where('is_active', true)->exists();

        if (!$emailActive && !$smsActive) {
            Log::error('Both email and SMS services are inactive. Cannot send notifications.');
            return;
        }

        $organizationIds = $options['organization_ids'] ?? json_decode($event->meeting->organizations, true) ?? [];
        $users = !empty($organizationIds)
            ? User::whereIn('organization_id', $organizationIds)->get()
            : collect();
        $externalContacts = $event->meeting->externalContacts ?? collect();

        if ($users->isEmpty() && $externalContacts->isEmpty()) {
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
            if ($sendEmail && $emailActive) {
                try {
                    $user->notify(new MeetingStatusNotification($event->meeting, $event->notificationType, false));
                    $notifiedUserIds[] = $user->id;
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
            if ($sendEmail && $emailActive) {
                if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning("Invalid email for external contact #{$contact->id}: {$contact->email}");
                    continue;
                }

                try {
                    $contact->notify(new MeetingStatusNotification($event->meeting, $event->notificationType, true));
                    $notifiedExternalIds[] = $contact->id;
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
        // Use recipient's name if available, otherwise fallback to a neutral greeting
        // $name = $recipient->name ?? 'Sir/Madam';
        $name =  'Sir/Madam';
        $appName = config('app.name');
        $meetingTitle = $meeting->title;
    
        // Safely parse meeting date and start time with fallback
        try {
            $meetingDate = Carbon::parse($meeting->meeting_date_ad);
            $startTime = Carbon::parse($meeting->start_time);
    
            // Convert to Nepali date using the helper function
            $nepaliDate = NepaliDateConverter::toNepaliDate($meetingDate); // Adjust based on your class/namespace

            // Convert day and year to Nepali digits
            // $nepaliDay = NepaliDateConverter::toNepaliDigits($nepaliDate['day']);
            // $nepaliYear = NepaliDateConverter::toNepaliDigits($nepaliDate['year']);
            $nepaliDay= $nepaliDate['day'];
            $nepaliYear= $nepaliDate['year'];


            $nepaliFormattedDate = "{$nepaliDate['english_month_name']} {$nepaliDay}, {$nepaliYear}";

            // Format time to 12-hour format and convert to Nepali digits
            $hour = $startTime->format('g'); // Hour without leading zero (e.g., "2")
            // $nepaliHour = NepaliDateConverter::toNepaliDigits($hour);
            $nepaliHour = $hour;
            // $period = $startTime->format('A') === 'AM' ? 'बिहान' : 'बेलुका'; // AM = बिहान, PM = बेलुका
            $period = $startTime->format('A') === 'AM' ? 'AM' : 'PM'; // AM = बिहान, PM = बेलुका
            $formattedTime = "{$nepaliHour} {$period} "; // e.g., "२ बजे"

            // Combine Nepali date and time
            $formattedDateTime = "{$nepaliFormattedDate}, {$formattedTime}";
        
            // Combine Nepali date and time;
        } catch (\Exception $e) {
            Log::warning("Invalid date or time for meeting #{$meeting->id}: {$e->getMessage()}");
            $formattedDateTime = 'at a scheduled date and time'; // Fallback message
        }
    
        // Standardize SMS message format based on notification type
        switch ($notificationType) {
            case 'scheduled':
                return "Dear {$name}, you are cordially invited to attend \"{$meetingTitle}\" scheduled for {$formattedDateTime} at {$meeting->meeting_location}.";
            case 'reminder':
                    return "Reminder: Dear {$name}, This is a gentle reminder for \"{$meetingTitle}\" today at {$startTime}  {$meeting->meeting_location}.";
            case 'rescheduled':
                return "Dear {$name}, This is to inform you that the \"{$meetingTitle}\" has been rescheduled to {$formattedDateTime} at {$meeting->meeting_location}.We apologize for any inconvenience caused.";
            case 'cancellation':
                $reason = isset($options['reason']) ? " Reason: {$options['reason']}" : '';
                return "Dear {$name}, This is to inform you that the \"{$meetingTitle}\" scheduled for {$formattedDateTime} has been cancelled. Sorry for the inconvenience.";

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
        } catch (\Exception $e) {
            Log::error("Failed to save {$notificationType } notification record for {$type}: {$e->getMessage()}");
        }
    }
}