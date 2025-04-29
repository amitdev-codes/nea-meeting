<?php

namespace Modules\GoogleCalendar\Services;

use Carbon\Carbon;
use App\Models\User;
use Spatie\GoogleCalendar\Event;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Log;
use Modules\NeaMeeting\Models\Meeting;
use Modules\GoogleCalendar\Models\GoogleCalendarSetting;


class GoogleCalendarService
{
    protected $settings;
    
    public function __construct()
    {
        // Get the settings (could be organization-specific or global)
        $this->settings = GoogleCalendarSetting::where('is_enabled', true)->first();
    }
    
    /**
     * Check if Google Calendar integration is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->settings && $this->settings->is_enabled;
    }
    
    /**
     * Configure Google Calendar with the current settings
     */
    protected function configureCalendar(): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        // Set the environment variables needed by the Spatie package
        config([
            'google-calendar.calendar_id' => $this->settings->google_calendar_id,
            'google-calendar.auth_profile' => $this->settings->auth_method,
        ]);
        
        if ($this->settings->auth_method === 'service_account') {
            // Path to the service account credentials JSON file
            config(['google-calendar.service_account_credentials_json' => $this->settings->service_account_json]);
        } else {
            // OAuth client settings
            config([
                'google-calendar.client_id' => $this->settings->client_id,
                'google-calendar.client_secret' => $this->settings->client_secret,
                'google-calendar.redirect_uri' => $this->settings->redirect_uri,
            ]);
        }
    }
    
    /**
     * Create a Google Calendar event for a meeting
     *
     * @param Meeting $meeting
     * @return Event|null
     */
    public function createEvent(Meeting $meeting)
    {
        if (!$this->isEnabled()) {
            return null;
        }
        
        try {
            $this->configureCalendar();
            
            $event = new Event;
            $event->name = $meeting->title;
            $event->description = $meeting->description ?? '';
            
            // Convert start_time and end_time to Carbon instances
            $startTime = Carbon::parse($meeting->start_time);
            $endTime = $meeting->end_time ? Carbon::parse($meeting->end_time) : $startTime->copy()->addHour();
            
            $event->startDateTime = $startTime;
            $event->endDateTime = $endTime;
            
            // Add location if available
            if (!empty($meeting->meeting_location)) {
                $event->location = $meeting->meeting_location;
            } elseif (!empty($meeting->meeting_room_id)) {
                $event->location = $meeting->meetingRoom->name ?? '';
            }
            
            // Add meeting link for virtual meetings
            if ($meeting->is_virtual && !empty($meeting->virtual_meeting_link)) {
                $event->description .= "\n\nMeeting Link: " . $meeting->virtual_meeting_link;
            }
            
            // Add attendees
            $attendees = $this->getAttendees($meeting);
            if (!empty($attendees)) {
                $event->addAttendee($attendees);
            }
            
            // Save event to Google Calendar
            $googleEvent = $event->save();
            
            // Save Google Calendar event ID to meeting
            $meeting->google_calendar_event_id = $googleEvent->id;
            $meeting->google_calendar_link = $googleEvent->htmlLink;
            $meeting->save();
            
            return $googleEvent;
        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update an existing Google Calendar event
     *
     * @param Meeting $meeting
     * @return Event|null
     */
    public function updateEvent(Meeting $meeting)
    {
        if (!$this->isEnabled()) {
            return null;
        }
        
        try {
            $this->configureCalendar();
            
            // If no Google Calendar event ID exists, create new event
            if (empty($meeting->google_calendar_event_id)) {
                return $this->createEvent($meeting);
            }
            
            // Get the existing event
            $event = Event::find($meeting->google_calendar_event_id);
            if (!$event) {
                // Event might have been deleted from Google Calendar, create a new one
                return $this->createEvent($meeting);
            }
            
            // Update event details
            $event->name = $meeting->title;
            $event->description = $meeting->description ?? '';
            
            // If meeting is cancelled, add that to the description
            if ($meeting->status === 'Cancelled') {
                $event->description = 'CANCELLED: ' . $event->description;
            }
            
            // Convert start_time and end_time to Carbon instances
            $startTime = Carbon::parse($meeting->start_time);
            $endTime = $meeting->end_time ? Carbon::parse($meeting->end_time) : $startTime->copy()->addHour();
            
            $event->startDateTime = $startTime;
            $event->endDateTime = $endTime;
            
            // Add location if available
            if (!empty($meeting->meeting_location)) {
                $event->location = $meeting->meeting_location;
            } elseif (!empty($meeting->meeting_room_id)) {
                $event->location = $meeting->meetingRoom->name ?? '';
            }
            
            // Add meeting link for virtual meetings
            if ($meeting->is_virtual && !empty($meeting->virtual_meeting_link)) {
                if (!str_contains($event->description, 'Meeting Link:')) {
                    $event->description .= "\n\nMeeting Link: " . $meeting->virtual_meeting_link;
                }
            }
            
            // Update attendees
            $attendees = $this->getAttendees($meeting);
            if (!empty($attendees)) {
                $event->attendees = $attendees;
            }
            
            // Save updated event
            $googleEvent = $event->save();
            
            // Update link in case it changed
            $meeting->google_calendar_link = $googleEvent->htmlLink;
            $meeting->save();
            
            return $googleEvent;
        } catch (\Exception $e) {
            Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete a Google Calendar event
     *
     * @param Meeting $meeting
     * @return bool
     */
    public function deleteEvent(Meeting $meeting)
    {
        if (!$this->isEnabled() || empty($meeting->google_calendar_event_id)) {
            return false;
        }
        
        try {
            $this->configureCalendar();
            
            $event = Event::find($meeting->google_calendar_event_id);
            if ($event) {
                $event->delete();
            }
            
            // Remove Google Calendar data from meeting
            $meeting->google_calendar_event_id = null;
            $meeting->google_calendar_link = null;
            $meeting->save();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete Google Calendar event: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get formatted attendees list from meeting
     *
     * @param Meeting $meeting
     * @return array
     */
    protected function getAttendees(Meeting $meeting)
    {
        $attendees = [];
        
        // Add internal users
        $organizationIds = $meeting->organizations ? json_decode($meeting->organizations, true) : [];
        if (!empty($organizationIds)) {
            $users = \App\Models\User::whereIn('organization_id', $organizationIds)->get();
            foreach ($users as $user) {
                if (!empty($user->email)) {
                    $attendees[] = ['email' => $user->email, 'name' => $user->name];
                }
            }
        }
        
        // Add external contacts
        if ($meeting->externalContacts && $meeting->externalContacts->isNotEmpty()) {
            foreach ($meeting->externalContacts as $contact) {
                if (!empty($contact->email)) {
                    $attendees[] = ['email' => $contact->email, 'name' => $contact->name];
                }
            }
        }
        
        return $attendees;
    }
}