<?php

namespace App\Listeners;

use Log;
use App\Models\User;
use App\Events\MeetingCreated;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\MeetingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\NeaMeeting\Models\Notification;
use App\Notifications\ExternalMeetingNotification;

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
        $users = !empty($organizationIds) ? User::whereIn('organization_id', $organizationIds)->get() : collect();
        $externalContacts = $event->meeting->externalContacts ?? collect();
        if ($users->isEmpty() && $externalContacts->isEmpty()) {
            return;
        }

        // Send notifications to users
        try{
            $userIds = [];
            foreach ($users as $user) {
                try {
                    $user->notify(new MeetingNotification($event->meeting));
                    $userIds[] = $user->id;
                } catch (\Exception $e) {
                    \Log::error('Failed to send meeting notification for user #' . $user->id . ': ' . $e->getMessage());
                }
            }
            // Store the notified users in a single record
            if (!empty($userIds)) {
                $event->meeting->notifiedUsers()->create([
                    'users' => json_encode($userIds), // Store user IDs as JSON
                    'notified_at' => now(),
                    'notification_type' => 'email',
                    'notification_status' => 'sent',
                ]);
            }
        }catch (\Exception $e) {
            \Log::error('Failed to save meeting notification record: ' . $e->getMessage());
        }

            // Send notifications to external contacts
            try {
                $externalContactIds = [];
                foreach ($externalContacts as $contact) {
                    try {
                        if (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                            \Log::warning('Invalid email for external contact #' . $contact->id . ': ' . $contact->email);
                            continue;
                        }
                        $contact->notify(new ExternalMeetingNotification($event->meeting));
                        $externalContactIds[] = $contact->id;
                    } catch (\Exception $e) {
                        \Log::error('Failed to send meeting notification to external contact #' . $contact->id . ': ' . $e->getMessage());
                    }
                }

                // Store notified external contacts
                if (!empty($externalContactIds)) {
                    $event->meeting->notifiedExternalContacts()->create([
                        'external_contacts' => json_encode($externalContactIds),
                        'notified_at' => now(),
                        'notification_type' => 'email',
                        'notification_status' => 'sent',
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to save meeting notification record for external contacts: ' . $e->getMessage());
            }
    }
}