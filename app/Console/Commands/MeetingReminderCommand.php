<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Events\MeetingEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;
use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Models\EmailConfiguration;
use App\Notifications\MeetingReminderNotification;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\Services\Sms\SmsServiceInterface;

class MeetingReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:send-reminders {--type=all : Reminder type (daily, twoHour, halfHour, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for meetings based on different timings';

    /**
     * Reminder configuration - easily enable/disable different reminder times
     * 
     * @var array
     */
    protected $reminderConfig = [
        'daily' => [
            'enabled' => true,
            'description' => 'Daily reminder at 10 AM for today\'s meetings'
        ],
        'twoHour' => [
            'enabled' => true,
            'description' => 'Reminder 2 hours before meeting'
        ],
        'halfHour' => [
            'enabled' => true,
            'description' => 'Reminder 30 minutes before meeting'
        ]
    ];

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
        try {
            $type = $this->option('type');
            $this->info('Starting to send meeting reminders... Type: ' . $type);
            Log::info('Meeting reminder command started. Type: ' . $type);

            // Check if notifications services are active
            $emailActive = EmailConfiguration::where('is_active', true)->exists();
            $smsActive = SmsConfiguration::where('is_active', true)->exists();

            Log::info("Email active: " . ($emailActive ? 'Yes' : 'No') . ", SMS active: " . ($smsActive ? 'Yes' : 'No'));

            if (!$emailActive && !$smsActive) {
                $this->error('Both email and SMS services are inactive. Cannot send any notifications.');
                Log::error('Both email and SMS services are inactive. Cannot send any notifications.');
                return 1;
            }


            if ($type === 'all' || $type === 'twoHour') {
                if ($this->reminderConfig['twoHour']['enabled']) {
                    $this->processTwoHourReminders($emailActive, $smsActive);
                } else {
                    $this->info('Two-hour reminders are disabled in configuration.');
                }
            }

            $this->info('Meeting reminders job completed.');
            Log::info('Meeting reminders job completed');
            return 0;
        } catch (\Exception $e) {
            Log::error('Error in meeting reminder command: ' . $e->getMessage());
            $this->error('Error in meeting reminder command: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Process daily reminders at 10 AM for today's meetings
     * 
     * @param bool $emailActive
     * @param bool $smsActive
     */
    protected function processDailyReminders($emailActive, $smsActive)
    {
        $this->info('Processing daily reminders (10 AM) for today\'s meetings');
        Log::info('Processing daily reminders (10 AM) for today\'s meetings');

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Find all meetings scheduled for today
        $meetings = Meeting::whereBetween('start_time', [$today, $tomorrow])
            ->where('status', '!=', 'cancelled')
            ->get();

        $this->info("Found {$meetings->count()} meetings scheduled for today.");
        Log::info("Found {$meetings->count()} meetings for daily reminder");

        if ($meetings->isEmpty()) {
            $this->info('No meetings found for today.');
            return;
        }

        foreach ($meetings as $meeting) {
            $this->sendRemindersForMeeting($meeting, $emailActive, $smsActive, 'daily');
        }
    }

    /**
     * Process 2-hour reminders before meetings
     * 
     * @param bool $emailActive
     * @param bool $smsActive
     */
    protected function processTwoHourReminders($emailActive, $smsActive)
    {
        $this->info('Processing 2-hour reminders before meetings');
        Log::info('Processing 2-hour reminders before meetings');

        $now = Carbon::now();
        $targetStart = $now->copy()->addHours(2)->subMinutes(5); // 1:55 hours from now
        $targetEnd = $now->copy()->addHours(2)->addMinutes(5);   // 2:05 hours from now

        // Find meetings scheduled to occur within our target time window
        $meetings = Meeting::whereBetween('start_time', [$targetStart, $targetEnd])
            ->where('status', '!=', 'cancelled')
            ->get();

        $this->info("Found {$meetings->count()} meetings scheduled for around 2 hours from now.");
        Log::info("Found {$meetings->count()} meetings for 2-hour reminder");

        if ($meetings->isEmpty()) {
            $this->info('No upcoming meetings requiring 2-hour reminders.');
            return;
        }

        foreach ($meetings as $meeting) {
            $this->sendRemindersForMeeting($meeting, $emailActive, $smsActive, 'twoHour');
        }
    }

    /**
     * Process 30-minute reminders before meetings
     * 
     * @param bool $emailActive
     * @param bool $smsActive
     */
    protected function processHalfHourReminders($emailActive, $smsActive)
    {
        $this->info('Processing 30-minute reminders before meetings');
        Log::info('Processing 30-minute reminders before meetings');

        $now = Carbon::now();
        $targetStart = $now->copy()->addMinutes(30)->subMinutes(2); // 28 minutes from now
        $targetEnd = $now->copy()->addMinutes(30)->addMinutes(2);   // 32 minutes from now

        // Find meetings scheduled to occur within our target time window
        $meetings = Meeting::whereBetween('start_time', [$targetStart, $targetEnd])
            ->where('status', '!=', 'cancelled')
            ->get();

        $this->info("Found {$meetings->count()} meetings scheduled for around 30 minutes from now.");
        Log::info("Found {$meetings->count()} meetings for 30-minute reminder");

        if ($meetings->isEmpty()) {
            $this->info('No upcoming meetings requiring 30-minute reminders.');
            return;
        }

        foreach ($meetings as $meeting) {
            $this->sendRemindersForMeeting($meeting, $emailActive, $smsActive, 'halfHour');
        }
    }

    /**
     * Send reminders for a specific meeting
     * 
     * @param Meeting $meeting
     * @param bool $emailActive
     * @param bool $smsActive
     * @param string $reminderType
     */
    protected function sendRemindersForMeeting($meeting, $emailActive, $smsActive, $reminderType)
    {
        try {
            $this->info("Processing {$reminderType} reminder for meeting: {$meeting->title} (ID: {$meeting->id})");
            
            // Get organization IDs from the meeting's JSON column
            $organizationIds = is_array($meeting->organizations) ? $meeting->organizations : 
                (is_string($meeting->organizations) ? json_decode($meeting->organizations, true) : []);

            // Get users to notify
            $users = !empty($organizationIds) ? User::whereIn('organization_id', $organizationIds)->get() : collect();
            $externalContacts = !empty($meeting->external_contacts) ? json_decode($meeting->external_contacts, true) : [];

            Log::info("Found " . $users->count() . " internal users and " . count($externalContacts) . " external contacts");

            if ($users->isEmpty() && empty($externalContacts)) {
                $this->warn("No recipients found for meeting ID: {$meeting->id}");
                Log::warning("No recipients found for meeting ID: {$meeting->id}");
                return;
            }

            // Dispatch the meeting reminder event with the reminder type
            event(new MeetingEvent($meeting, 'reminder', [
                'send_email' => $emailActive,
                'send_sms' => $smsActive,
                'organization_ids' => $organizationIds,
                'reminder_type' => $reminderType
            ]));
            
            Log::info("{$reminderType} reminder event dispatched for meeting ID: {$meeting->id}");
        } catch (\Exception $e) {
            Log::error("Error sending {$reminderType} reminder for meeting ID {$meeting->id}: " . $e->getMessage());
            $this->error("Error sending {$reminderType} reminder for meeting ID {$meeting->id}: " . $e->getMessage());
        }
    }
}