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

class MeetingCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meeting;
    public $options;

    public function __construct(Meeting $meeting, array $options = [])
    {
        $this->meeting = $meeting;
        $this->options = $options;
    }
}
