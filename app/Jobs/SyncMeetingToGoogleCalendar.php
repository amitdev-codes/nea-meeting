<?php

namespace App\Jobs;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\GoogleCalendar\Services\GoogleCalendarService;
use Modules\NeaMeeting\Models\Meeting;

class SyncMeetingToGoogleCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30; // seconds between retries

    public function __construct(
        public readonly Meeting $meeting,
        public readonly array $organizationIds
    ) {}

    public function handle(GoogleCalendarService $googleCalendarService): void
    {
        if (! $googleCalendarService->isEnabled()) {
            return;
        }

        $users = User::whereIn('organization_id', $this->organizationIds)
            ->whereNotNull('google_access_token')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        $eventData = [
            'title'       => $this->meeting->title,
            'description' => $this->meeting->description,
            'start_time'  => $this->meeting->start_time,
            'end_time'    => $this->meeting->end_time,
            'attendees'   => $users->pluck('email')->toArray(),
        ];

        $googleEvent = $googleCalendarService->createEventForMultipleUsers($users, $eventData);

        if ($googleEvent) {
            Log::info('Google Calendar event created for meeting #' . $this->meeting->id);
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SyncMeetingToGoogleCalendar failed for meeting #' . $this->meeting->id . ': ' . $e->getMessage());
    }
}
