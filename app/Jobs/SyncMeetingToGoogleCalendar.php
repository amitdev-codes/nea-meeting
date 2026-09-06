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

        $users = User::whereNotNull('google_access_token')
            ->where(function ($query) {
                $query->where('id', $this->meeting->created_by);

                if (!empty($this->organizationIds)) {
                    $query->orWhereIn('organization_id', $this->organizationIds);
                }
            })
            ->get()
            ->sortByDesc(fn (User $user) => $user->id === $this->meeting->created_by)
            ->values();

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

        $googleEvents = $googleCalendarService->createEventForMultipleUsers($users, $eventData);

        $createdEvent = collect($googleEvents)->first(fn ($event) => $event);

        if ($createdEvent) {
            $this->meeting->update([
                'google_calendar_event_id' => $createdEvent->getId(),
                'google_calendar_link' => $createdEvent->getHtmlLink(),
            ]);

            Log::info('Google Calendar event created for meeting #' . $this->meeting->id);
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SyncMeetingToGoogleCalendar failed for meeting #' . $this->meeting->id . ': ' . $e->getMessage());
    }
}
