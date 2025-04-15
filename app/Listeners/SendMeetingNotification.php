<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\MeetingCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMeetingNotification implements ShouldQueue
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
    public function handle(MeetingCreated $event)
    {
        // Get employees/users who should be notified
        // This could be based on your business logic (e.g., all employees, specific department, etc.)
        $users = User::role(['admin', 'employee'])->get();// Adjust this query as needed
        
        foreach ($users as $user) {
            $user->notify(new MeetingNotification($event->meeting));
        }
    }
}
