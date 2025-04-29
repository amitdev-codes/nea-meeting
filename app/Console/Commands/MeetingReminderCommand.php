<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Models\EmailConfiguration;
use App\Notifications\MeetingReminderNotification;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\Services\Sms\SmsServiceInterface;
use App\Notifications\ExternalMeetingReminderNotification;

class MeetingReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications 2 hours before meetings';

    protected $emailService;
    protected $smsService;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->emailService = app(DynamicEmailService::class);
        $this->smsService = app(SmsServiceInterface::class);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send meeting reminders...');

        // Get current time
        $now = Carbon::now();
        
        // Calculate the target time window (meetings in ~2 hours)
        $targetStart = $now->copy()->addHours(2)->subMinutes(5); // 1:55 hours from now
        $targetEnd = $now->copy()->addHours(2)->addMinutes(5);   // 2:05 hours from now

        // Find meetings scheduled to occur within our target time window
        $meetings = Meeting::whereBetween('start_time', [$targetStart, $targetEnd])
            ->where('status', '!=', 'cancelled')
            ->get();

        $this->info("Found {$meetings->count()} meetings scheduled for around 2 hours from now.");

        if ($meetings->isEmpty()) {
            $this->info('No upcoming meetings requiring reminders.');
            return 0;
        }

        // Check if notifications services are active
        $emailActive = EmailConfiguration::where('is_active', true)->exists();
        $smsActive = SmsConfiguration::where('is_active', true)->exists();

        // dd($emailActive, $smsActive);

        if (!$emailActive && !$smsActive) {
            $this->error('Both email and SMS services are inactive. Cannot send any notifications.');
            return 1;
        }

        foreach ($meetings as $meeting) {
            $this->sendRemindersForMeeting($meeting, $emailActive, $smsActive);
        }

        $this->info('Meeting reminders job completed.');
        return 0;
    }

    /**
     * Send reminders for a specific meeting
     * 
     * @param Meeting $meeting
     * @param bool $emailActive
     * @param bool $smsActive
     */
    protected function sendRemindersForMeeting($meeting, $emailActive, $smsActive)
    {
        $this->info("Processing reminders for meeting: {$meeting->title} (ID: {$meeting->id})");

        // Get organization IDs from the meeting's JSON column
        $organizationIds = $meeting->organizations ?? [];
        
        // Get users to notify
        $users = !empty($organizationIds) ? \App\Models\User::whereIn('organization_id', $organizationIds)->get() : collect();
        $externalContacts = $meeting->externalContacts ?? collect();

        if ($users->isEmpty() && $externalContacts->isEmpty()) {
            $this->warn("No recipients found for meeting ID: {$meeting->id}");
            return;
        }

        // Track notification results
        $notifiedUserIds = [];
        $smsUserIds = [];
        $notifiedExternalIds = [];
        $smsExternalIds = [];

        // Process internal users
        foreach ($users as $user) {
            // Send email reminder
            if ($emailActive) {
                try {
                    $user->notify(new MeetingReminderNotification($meeting));
                    $notifiedUserIds[] = $user->id;
                    $this->line("Email reminder sent to user: {$user->name} ({$user->email})");
                } catch (\Exception $e) {
                    $this->error("Failed to send email reminder to user #{$user->id}: {$e->getMessage()}");
                    Log::error("Failed to send meeting reminder email for user #{$user->id}: {$e->getMessage()}");
                }
            }

            // Send SMS reminder
            if ($smsActive && !empty($user->mobile_no)) {
                try {
                    $message = $this->formatSmsMessage($meeting, $user);
                    $result = $this->smsService->send($user->mobile_no, $message);
                    
                    if ($result['success']) {
                        $smsUserIds[] = $user->id;
                        $this->line("SMS reminder sent to user: {$user->name} ({$user->mobile_no})");
                    } else {
                        $this->error("Failed to send SMS reminder to user #{$user->id}: {$result['message']}");
                        Log::error("Failed to send meeting reminder SMS for user #{$user->id}: {$result['message']}");
                    }
                } catch (\Exception $e) {
                    $this->error("SMS service error for user #{$user->id}: {$e->getMessage()}");
                    Log::error("SMS service error for user #{$user->id}: {$e->getMessage()}");
                }
            }
        }

        // Process external contacts
        foreach ($externalContacts as $contact) {
            // Send email reminder
            if ($emailActive) {
                try {
                    if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                        $this->warn("Invalid email for external contact #{$contact->id}: {$contact->email}");
                        Log::warning("Invalid email for external contact #{$contact->id}: {$contact->email}");
                        continue;
                    }
                    
                    $contact->notify(new ExternalMeetingReminderNotification($meeting));
                    $notifiedExternalIds[] = $contact->id;
                    $this->line("Email reminder sent to external contact: {$contact->name} ({$contact->email})");
                } catch (\Exception $e) {
                    $this->error("Failed to send email reminder to external contact #{$contact->id}: {$e->getMessage()}");
                    Log::error("Failed to send meeting reminder email for external contact #{$contact->id}: {$e->getMessage()}");
                }
            }

            // Send SMS reminder
            if ($smsActive && !empty($contact->mobile_no)) {
                try {
                    $message = $this->formatSmsMessage($meeting, $contact, true);
                    $result = $this->smsService->send($contact->mobile_no, $message);
                    
                    if ($result['success']) {
                        $smsExternalIds[] = $contact->id;
                        $this->line("SMS reminder sent to external contact: {$contact->name} ({$contact->mobile_no})");
                    } else {
                        $this->error("Failed to send SMS reminder to external contact #{$contact->id}: {$result['message']}");
                        Log::error("Failed to send meeting reminder SMS for external contact #{$contact->id}: {$result['message']}");
                    }
                } catch (\Exception $e) {
                    $this->error("SMS service error for external contact #{$contact->id}: {$e->getMessage()}");
                    Log::error("SMS service error for external contact #{$contact->id}: {$e->getMessage()}");
                }
            }
        }

        // Record notifications in database
        try {
            // Record email notifications for users
            if (!empty($notifiedUserIds)) {
                $meeting->notifiedUsers()->create([
                    'users' => json_encode($notifiedUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'email_reminder',
                    'notification_status' => 'sent',
                ]);
            }

            // Record SMS notifications for users
            if (!empty($smsUserIds)) {
                $meeting->notifiedUsers()->create([
                    'users' => json_encode($smsUserIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms_reminder',
                    'notification_status' => 'sent',
                ]);
            }

            // Record email notifications for external contacts
            if (!empty($notifiedExternalIds)) {
                $meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($notifiedExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'email_reminder',
                    'notification_status' => 'sent',
                ]);
            }

            // Record SMS notifications for external contacts
            if (!empty($smsExternalIds)) {
                $meeting->notifiedExternalContacts()->create([
                    'external_contacts' => json_encode($smsExternalIds),
                    'notified_at' => now(),
                    'notification_type' => 'sms_reminder',
                    'notification_status' => 'sent',
                ]);
            }
        } catch (\Exception $e) {
            $this->error("Failed to save notification records: {$e->getMessage()}");
            Log::error("Failed to save meeting reminder notification records: {$e->getMessage()}");
        }
    }

    /**
     * Format the SMS message with meeting details
     *
     * @param Meeting $meeting
     * @param User|ExternalContact $recipient
     * @param bool $isExternal
     * @return string
     */
    protected function formatSmsMessage($meeting, $recipient, $isExternal = false)
    {
        $name = $recipient->name;
        $appName = config('app.name');
        $meetingTitle = $meeting->title;
        $formattedDateTime = Carbon::parse($meeting->scheduled_at)->format('M d, Y \a\t h:i A');

        return "Reminder: Hi {$name}, your meeting \"{$meetingTitle}\" is scheduled in 2 hours ({$formattedDateTime}). - {$appName}";
    }
}