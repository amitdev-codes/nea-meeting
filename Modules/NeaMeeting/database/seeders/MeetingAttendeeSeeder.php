<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingAttendee;

class MeetingAttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }
        
        $meetingIds = Meeting::pluck('id')->toArray();
        if (empty($meetingIds)) {
            $this->command->error('No meetings found in the database. Please seed meetings first.');
            return;
        }
        
        $attendanceStatuses = ['pending', 'confirmed', 'declined', 'attended', 'absent'];
        $attendees = [];
        
        foreach ($meetingIds as $meetingId) {
            // Determine meeting creator (we'll always include them as an attendee)
            $creatorId = Meeting::find($meetingId)->created_by;
            
            // Add creator as required attendee who confirmed
            $attendees[] = [
                'meeting_id' => $meetingId,
                'user_id' => $creatorId,
                'is_required' => true,
                'attendance_status' => 'confirmed',
                'invitation_sent_at' => Carbon::now()->subDays(rand(5, 15)),
                'response_at' => Carbon::now()->subDays(rand(1, 4)),
                'notes' => null,
                'created_at' => Carbon::now()->subDays(rand(5, 15)),
                'updated_at' => Carbon::now()->subDays(rand(1, 4)),
            ];
            
            // Add random attendees (3-8 per meeting)
            $attendeeCount = rand(3, 8);
            $meetingAttendeeIds = array_diff($userIds, [$creatorId]); // Exclude creator
            shuffle($meetingAttendeeIds);
            
            for ($i = 0; $i < min($attendeeCount, count($meetingAttendeeIds)); $i++) {
                $isRequired = rand(0, 1) == 1;
                $status = $attendanceStatuses[array_rand($attendanceStatuses)];
                
                $attendees[] = [
                    'meeting_id' => $meetingId,
                    'user_id' => $meetingAttendeeIds[$i],
                    'is_required' => $isRequired,
                    'attendance_status' => $status,
                    'invitation_sent_at' => Carbon::now()->subDays(rand(5, 15)),
                    'response_at' => in_array($status, ['confirmed', 'declined']) ? Carbon::now()->subDays(rand(1, 4)) : null,
                    'notes' => rand(0, 5) == 0 ? 'Sample attendee note' : null,
                    'created_at' => Carbon::now()->subDays(rand(5, 15)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 4)),
                ];
            }
        }
        
        // Insert attendees in batches to avoid potential issues with large datasets
        foreach (array_chunk($attendees, 100) as $chunk) {
            MeetingAttendee::insert($chunk);
        }
    }
}
