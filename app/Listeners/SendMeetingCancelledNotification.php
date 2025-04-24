<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\MeetingCancelled;
use App\Mail\MeetingCancelledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMeetingCancelledNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MeetingCancelled $event)
    {

        if ($event->options['send_email'] ?? false) {
            // Fetch users to notify (e.g., attendees, organizers, or based on organization_ids)
            $users = User::whereIn('organization_id', $event->options['organization_ids'] ?? [])
                ->orWhereIn('id', $event->meeting->attendees->pluck('id'))
                ->get();
                // dd($users);

            foreach ($users as $user) {
          
                Mail::to($user->email)->send(new MeetingCancelledMail($event->meeting, $user, $event->options['reason'] ?? ''));
            }
        }
    }
}
