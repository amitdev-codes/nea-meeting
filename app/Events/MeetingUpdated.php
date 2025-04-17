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

class MeetingUpdated
{
    use Dispatchable, SerializesModels;

    public $meeting;
    public $options;

    /**
     * Create a new event instance.
     */
    public function __construct(Meeting $meeting, array $options = [])
    {
        $this->meeting = $meeting;
        $this->options = $options;
    }
}
