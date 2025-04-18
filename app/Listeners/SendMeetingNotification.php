<?php

namespace App\Listeners;

use App\Events\MeetingCreated;
use App\Notifications\MeetingNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class SendMeetingNotification implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     */
    public function handle(MeetingCreated $event)
    {
        // Check if email notifications should be sent
        if (!($event->options['send_email'] ?? true)) {
            return;
        }
            // Get organization IDs from the meeting's JSON column
        $organizationIds = $event->options['organization_ids'] ?? json_decode($event->meeting->organizations, true) ?? [];

        if (empty($organizationIds)) {
            return;
        }
            // Fetch users belonging to the specified organizations
        $users =User::whereIn('organization_id', $organizationIds)->get();

        if ($users->isEmpty()) {
            return;
        }

        // Send notifications to users
        foreach ($users as $user) {
            try {
                // Send the meeting notification
                $user->notify(new MeetingNotification($event->meeting));
                // Track notified users
                $event->meeting->notifiedUsers()->create([
                    'user_id' => $user->id,
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send meeting notification for user #' . $user->id . ': ' . $e->getMessage());
            }
        }
    }
}