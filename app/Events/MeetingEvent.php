<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MeetingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meeting;
    public $notificationType;
    public $options;

    /**
     * Create a new event instance.
     *
     * @param Meeting $meeting
     * @param string $notificationType (scheduled, rescheduled, cancelled, reminder)
     * @param array $options
     */
    public function __construct(Meeting $meeting, string $notificationType, array $options = [])
    {
        $this->meeting = $meeting;
        $this->notificationType = $notificationType;
        $this->options = $options;
    }
}
