<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingAttendee;
use Modules\NeaMeeting\Models\MeetingReminder;

class MeetingReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
        {
            $meetingIds = Meeting::whereIn('status', ['scheduled', 'ongoing'])->pluck('id')->toArray();
            if (empty($meetingIds)) {
                $this->command->info('No upcoming meetings found. Skipping reminder seeding.');
                return;
            }
            
            $attendees = MeetingAttendee::whereIn('meeting_id', $meetingIds)
                        ->where('attendance_status', '!=', 'declined')
                        ->get()
                        ->groupBy('meeting_id');
            
            $reminders = [];
            
            foreach ($attendees as $meetingId => $meetingAttendees) {
                $meeting = Meeting::find($meetingId);
                
                foreach ($meetingAttendees as $attendee) {
                    // Create 1-day reminder for everyone
                    $reminderTime = Carbon::parse($meeting->start_time)->subDay();
                    $now = Carbon::now();
                    
                    $reminders[] = [
                        'meeting_id' => $meetingId,
                        'user_id' => $attendee->user_id,
                        'reminder_time' => $reminderTime,
                        'sent' => $reminderTime->lt($now),
                        'sent_at' => $reminderTime->lt($now) ? $reminderTime : null,
                        'created_at' => Carbon::parse($meeting->created_at),
                        'updated_at' => $reminderTime->lt($now) ? $reminderTime : Carbon::parse($meeting->created_at),
                    ];
                    
                    // Create 1-hour reminder for some
                    if (rand(0, 1)) {
                        $reminderTime = Carbon::parse($meeting->start_time)->subHour();
                        
                        $reminders[] = [
                            'meeting_id' => $meetingId,
                            'user_id' => $attendee->user_id,
                            'reminder_time' => $reminderTime,
                            'sent' => $reminderTime->lt($now),
                            'sent_at' => $reminderTime->lt($now) ? $reminderTime : null,
                            'created_at' => Carbon::parse($meeting->created_at),
                            'updated_at' => $reminderTime->lt($now) ? $reminderTime : Carbon::parse($meeting->created_at),
                        ];
                    }
                }
            }
            
            MeetingReminder::insert($reminders);
        }

}
