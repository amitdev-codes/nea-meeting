<?php
namespace App\Listeners;

use App\Events\MeetingUpdated;
use App\Notifications\MeetingUpdateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class SendMeetingUpdateNotification implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * Handle the event.
     */
    public function handle(MeetingUpdated $event)
    {
        $sendEmail = $event->options['send_email'] ?? true;
        $sendSms = $event->options['send_sms'] ?? true;
        
        // Get all attendees for this meeting
        $attendees = $event->meeting->attendees()->with('user')->get();
        
        if ($attendees->isEmpty()) {
            Log::info('No attendees found for meeting #' . $event->meeting->id);
            return;
        }
        
        foreach ($attendees as $attendee) {
            if (!$attendee->user) {
                continue;
            }
            
            // Create notification with proper channels based on options
            $channels = [];
            if ($sendEmail) {
                $channels[] = 'mail';
                $channels[] = 'database';
            }
            
            if ($sendSms && !empty($attendee->user->phone)) {
                $channels[] = 'nexmo';
            }
            
            if (empty($channels)) {
                continue;
            }
            
            try {
                $attendee->user->notify(new MeetingUpdateNotification($event->meeting, $channels));
                
                // Update the invitation_sent_at timestamp
                $attendee->update(['invitation_sent_at' => now()]);
            } catch (\Exception $e) {
                Log::error('Failed to send meeting update notification: ' . $e->getMessage());
            }
        }
    }
}