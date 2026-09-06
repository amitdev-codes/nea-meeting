<?php

namespace Modules\GoogleCalendar\Services;

use Carbon\Carbon;
use Google_Client;
use App\Models\User;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function createEventForUser(User $user, array $eventData)
    {
        if (!$user->google_access_token) {
            Log::warning('User does not have Google access token', ['user_id' => $user->id]);
            return false;
        }

        try {
            $client = $this->refreshTokenIfNeeded($user);
            $service = new Google_Service_Calendar($client);

            $googleEvent = $this->makeGoogleEvent($eventData);
            $createdEvent = $service->events->insert('primary', $googleEvent);

            Log::info('Google Calendar event created successfully', [
                'user_id'  => $user->id,
                'event_id' => $createdEvent->getId()
            ]);

            return $createdEvent;

        } catch (\Exception $e) {
            Log::error('Google Calendar Error: ' . $e->getMessage(), [
                'user_id'    => $user->id,
                'event_data' => $eventData
            ]);
            return false;
        }
    }

    public function createEventForMultipleUsers($users, array $eventData): array
    {
        $results = [];

        foreach ($users as $user) {
            $results[$user->id] = $this->createEventForUser($user, $eventData);
        }

        return $results;
    }

    public function updateEvent(Meeting $meeting, ?User $user = null)
    {
        if (!$meeting->google_calendar_event_id) {
            Log::warning('Meeting does not have Google Calendar event id', ['meeting_id' => $meeting->id]);
            return false;
        }

        foreach ($this->usersForMeetingEvent($meeting, $user) as $eventOwner) {
            $updatedEvent = $this->updateEventForUser(
                $eventOwner,
                $meeting->google_calendar_event_id,
                $this->eventDataFromMeeting($meeting)
            );

            if ($updatedEvent) {
                return $updatedEvent;
            }
        }

        Log::warning('Google Calendar event could not be updated for any meeting user', ['meeting_id' => $meeting->id]);
        return false;
    }

    public function updateEventForUser(User $user, string $eventId, array $eventData)
    {
        if (!$user->google_access_token) {
            Log::warning('User does not have Google access token', ['user_id' => $user->id]);
            return false;
        }

        try {
            $client = $this->refreshTokenIfNeeded($user);
            $service = new Google_Service_Calendar($client);

            $googleEvent = $this->makeGoogleEvent($eventData);
            $updatedEvent = $service->events->update('primary', $eventId, $googleEvent);

            Log::info('Google Calendar event updated successfully', [
                'user_id'  => $user->id,
                'event_id' => $updatedEvent->getId()
            ]);

            return $updatedEvent;

        } catch (\Exception $e) {
            Log::error('Google Calendar Update Error: ' . $e->getMessage(), [
                'user_id'    => $user->id,
                'event_id'   => $eventId,
                'event_data' => $eventData
            ]);
            return false;
        }
    }

    public function updateEventForMultipleUsers($users, array $eventIds, array $eventData): array
    {
        $results = [];

        foreach ($users as $user) {
            $eventId = $eventIds[$user->id] ?? null;

            if (!$eventId) {
                $results[$user->id] = false;
                continue;
            }

            $results[$user->id] = $this->updateEventForUser($user, $eventId, $eventData);
        }

        return $results;
    }

    public function deleteEvent(Meeting $meeting, ?User $user = null): bool
    {
        if (!$meeting->google_calendar_event_id) {
            Log::warning('Meeting does not have Google Calendar event id', ['meeting_id' => $meeting->id]);
            return false;
        }

        foreach ($this->usersForMeetingEvent($meeting, $user) as $eventOwner) {
            if ($this->deleteEventForUser($eventOwner, $meeting->google_calendar_event_id)) {
                return true;
            }
        }

        Log::warning('Google Calendar event could not be deleted for any meeting user', ['meeting_id' => $meeting->id]);
        return false;
    }

    public function deleteEventForUser(User $user, string $eventId): bool
    {
        if (!$user->google_access_token) {
            Log::warning('User does not have Google access token', ['user_id' => $user->id]);
            return false;
        }

        try {
            $client = $this->refreshTokenIfNeeded($user);
            $service = new Google_Service_Calendar($client);
            $service->events->delete('primary', $eventId);

            Log::info('Google Calendar event deleted successfully', [
                'user_id'  => $user->id,
                'event_id' => $eventId
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Google Calendar Delete Error: ' . $e->getMessage(), [
                'user_id'  => $user->id,
                'event_id' => $eventId
            ]);
            return false;
        }
    }

    public function deleteEventForMultipleUsers($users, array $eventIds): array
    {
        $results = [];

        foreach ($users as $user) {
            $eventId = $eventIds[$user->id] ?? null;

            if (!$eventId) {
                $results[$user->id] = false;
                continue;
            }

            $results[$user->id] = $this->deleteEventForUser($user, $eventId);
        }

        return $results;
    }

    private function eventDataFromMeeting(Meeting $meeting): array
    {
        return [
            'title'       => $meeting->title,
            'description' => $meeting->description,
            'start_time'  => $meeting->start_time,
            'end_time'    => $meeting->end_time,
        ];
    }

    private function usersForMeetingEvent(Meeting $meeting, ?User $user = null)
    {
        if ($user) {
            return collect([$user]);
        }

        $organizationIds = array_filter((array) $meeting->organizations);

        $users = User::query()
            ->whereNotNull('google_access_token')
            ->where(function ($query) use ($meeting, $organizationIds) {
                $query->where('id', $meeting->created_by);

                if (!empty($organizationIds)) {
                    $query->orWhereIn('organization_id', $organizationIds);
                }
            })
            ->get();

        return $users
            ->sortByDesc(fn(User $candidate) => $candidate->id === $meeting->created_by)
            ->values();
    }

    private function makeGoogleEvent(array $eventData): Google_Service_Calendar_Event
    {
        $startTime = Carbon::parse($eventData['start_time']);
        $endTime   = !empty($eventData['end_time'])
            ? Carbon::parse($eventData['end_time'])
            : $startTime->copy()->addHour();

        $googleEvent = new Google_Service_Calendar_Event([
            'summary'     => $eventData['title'],
            'description' => $eventData['description'] ?? '',
            'start'       => [
                'dateTime' => $startTime->toRfc3339String(),
                'timeZone' => 'Asia/Kathmandu',
            ],
            'end'         => [
                'dateTime' => $endTime->toRfc3339String(),
                'timeZone' => 'Asia/Kathmandu',
            ],
        ]);

        if (!empty($eventData['attendees'])) {
            $attendees = collect($eventData['attendees'])
                ->map(fn($email) => ['email' => $email])
                ->toArray();

            $googleEvent->setAttendees($attendees);
        }

        return $googleEvent;
    }

    // ─── KEY FIX ────────────────────────────────────────────────────────────────
    // 1. Refresh token is extracted from the stored JSON token (not a separate column)
    // 2. Refresh token is preserved after refresh (Google doesn't re-send it)
    // 3. json_encode() only applied when saving back, not double-encoded
    // ────────────────────────────────────────────────────────────────────────────
    private function refreshTokenIfNeeded(User $user): Google_Client
    {
        $client = clone $this->client;
        $client->setAccessToken($user->google_access_token);

        if ($client->isAccessTokenExpired()) {

            // Extract refresh_token from the stored JSON blob
            $tokenData = is_array($user->google_access_token)
                ? $user->google_access_token
                : json_decode($user->google_access_token, true);

            // Fall back to separate column if it exists
            $refreshToken = $tokenData['refresh_token'] ?? $user->google_refresh_token ?? null;

            if (!$refreshToken) {
                throw new \Exception('Refresh token not available for user: ' . $user->id);
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

            // Google does NOT return refresh_token on refresh — preserve the old one
            if (empty($newToken['refresh_token'])) {
                $newToken['refresh_token'] = $refreshToken;
            }

            // Check Google didn't return an error instead of a token
            if (isset($newToken['error'])) {
                throw new \Exception(
                    'Failed to refresh Google token for user ' . $user->id . ': ' . $newToken['error_description'] ?? $newToken['error']
                );
            }

            $user->update([
                'google_access_token' => json_encode($newToken)
            ]);

            // Set the new token on the client so the current request uses it
            $client->setAccessToken($newToken);

            Log::info('Google access token refreshed', ['user_id' => $user->id]);
        }

        return $client;
    }
}
