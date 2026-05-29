<?php

// app/Jobs/SendMeetingNotifications.php

namespace App\Jobs;

use App\Events\MeetingEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;

class SendMeetingNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public readonly Meeting $meeting,
        public readonly array $notificationOptions
    ) {}

    public function handle(): void
    {
        event(new MeetingEvent($this->meeting, 'scheduled', $this->notificationOptions));
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendMeetingNotifications failed for meeting #' . $this->meeting->id . ': ' . $e->getMessage());
    }
}
