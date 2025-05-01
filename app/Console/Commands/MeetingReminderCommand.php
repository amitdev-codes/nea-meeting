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

        event(new MeetingEvent($meeting, 'reminder', [
            'send_email' => $emailActive,
            'send_sms' => $smsActive,
            'organization_ids' => json_decode($meeting->organizations, true) ?? [],
        ]));

    }


}