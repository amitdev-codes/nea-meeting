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
// Add missing event import
use Modules\Settings\Services\Sms\SmsServiceInterface;



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
        try {
            $this->info('Starting to send meeting reminders...');
            Log::info('Meeting reminder command started');

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
            Log::info("Found {$meetings->count()} meetings for reminder");

            if ($meetings->isEmpty()) {
                $this->info('No upcoming meetings requiring reminders.');
                Log::info('No upcoming meetings requiring reminders');
                return 0;
            }

            // Check if notifications services are active
            $emailActive = EmailConfiguration::where('is_active', true)->exists();
            $smsActive = SmsConfiguration::where('is_active', true)->exists();

            Log::info("Email active: " . ($emailActive ? 'Yes' : 'No') . ", SMS active: " . ($smsActive ? 'Yes' : 'No'));

            if (!$emailActive && !$smsActive) {
                $this->error('Both email and SMS services are inactive. Cannot send any notifications.');
                Log::error('Both email and SMS services are inactive. Cannot send any notifications.');
                return 1;
            }

            foreach ($meetings as $meeting) {
                $this->sendRemindersForMeeting($meeting, $emailActive, $smsActive);
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
     * Send reminders for a specific meeting
     * 
     * @param Meeting $meeting
     * @param bool $emailActive
     * @param bool $smsActive
     */
    protected function sendRemindersForMeeting($meeting, $emailActive, $smsActive)
    {
        try {
            $this->info("Processing reminders for meeting: {$meeting->title} (ID: {$meeting->id})");
            Log::info("Processing reminders for meeting: {$meeting->title} (ID: {$meeting->id})");

            // Get organization IDs from the meeting's JSON column
            $organizationIds = is_array($meeting->organizations) ? $meeting->organizations : 
            (is_string($meeting->organizations) ? json_decode($meeting->organizations, true) : []);

            // Get users to notify
            $users = !empty($organizationIds) ? \App\Models\User::whereIn('organization_id', $organizationIds)->get() : collect();
            $externalContacts = !empty($meeting->external_contacts) ? json_decode($meeting->external_contacts, true) : [];

            Log::info("Found " . $users->count() . " internal users and " . count($externalContacts) . " external contacts");

            if ($users->isEmpty() && empty($externalContacts)) {
                $this->warn("No recipients found for meeting ID: {$meeting->id}");
                Log::warning("No recipients found for meeting ID: {$meeting->id}");
                return;
            }

            // Dispatch the meeting reminder event
            event(new MeetingEvent($meeting, 'reminder', [
                'send_email' => $emailActive,
                'send_sms' => $smsActive,
                'organization_ids' => $organizationIds,
            ]));
            
            Log::info("Reminder event dispatched for meeting ID: {$meeting->id}");
        } catch (\Exception $e) {
            Log::error("Error sending reminder for meeting ID {$meeting->id}: " . $e->getMessage());
            $this->error("Error sending reminder for meeting ID {$meeting->id}: " . $e->getMessage());
        }
    }
}