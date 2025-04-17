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

        // Get attendees for the meeting
        $attendees = $event->meeting->attendees()->with('user')->get();

        if ($attendees->isEmpty()) {
            return;
        }

        // Send notifications to attendees
        foreach ($attendees as $attendee) {
            try {
                $user = $attendee->user;
                if ($user) {
                    $user->notify(new MeetingNotification($event->meeting));

                    // Update invitation_sent_at if not already set
                    if (!$attendee->invitation_sent_at) {
                        $attendee->update(['invitation_sent_at' => now()]);
                    }

                    // Track notified users
                    $event->meeting->notifiedUsers()->create([
                        'user_id' => $user->id,
                        'notified_at' => now(),
                        'notification_type' => 'email',
                        'notification_status' => 'sent',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send meeting notification for user #' . ($user->id ?? 'unknown') . ': ' . $e->getMessage());
            }
        }
    }
}