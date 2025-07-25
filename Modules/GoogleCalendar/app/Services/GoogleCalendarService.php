<?php

namespace Modules\GoogleCalendar\Services;

use Carbon\Carbon;
use Google_Client;
use App\Models\User;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Log;

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
        // return $this->settings && $this->settings->is_enabled;
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
            
            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $eventData['title'],
                'description' => $eventData['description'] ?? '',
                'start' => [
                    'dateTime' => Carbon::parse($eventData['start_time'])->toRfc3339String(),
                    'timeZone' => 'Asia/Kathmandu',
                ],
                'end' => [
                    'dateTime' => Carbon::parse($eventData['end_time'])->toRfc3339String(),
                    'timeZone' => 'Asia/Kathmandu',
                ],
            ]);
            
            if (!empty($eventData['attendees'])) {
                $attendees = collect($eventData['attendees'])->map(function ($email) {
                    return ['email' => $email];
                })->toArray();
                $googleEvent->setAttendees($attendees);
            }

            $createdEvent = $service->events->insert('primary', $googleEvent);
            
            Log::info('Google Calendar event created successfully', [
                'user_id' => $user->id,
                'event_id' => $createdEvent->getId()
            ]);
            
            return $createdEvent;
            
        } catch (\Exception $e) {
            Log::error('Google Calendar Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'event_data' => $eventData
            ]);
            return false;
        }
    }

    public function createEventForMultipleUsers($users, array $eventData): array
    {
        $results = [];
        
        foreach ($users as $user) {
            // dd($user);
            $results[$user->id] = $this->createEventForUser($user, $eventData);
        }

        return $results;
    }

    private function refreshTokenIfNeeded(User $user): Google_Client
    {
        $client = clone $this->client;
        $client->setAccessToken($user->google_access_token);

        if ($client->isAccessTokenExpired()) {
            if (!$user->google_refresh_token) {
                throw new \Exception('Refresh token not available for user: ' . $user->id);
            }
            
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $newToken = $client->getAccessToken();
            
            $user->update([
                'google_access_token' => json_encode($newToken)
            ]);
            
            Log::info('Google access token refreshed', ['user_id' => $user->id]);
        }
        
        return $client;
    }
}