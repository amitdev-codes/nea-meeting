<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingRoom;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all user IDs - we'll assume you have some users in the database
        $userIds = DB::table('users')->pluck('id')->toArray();
        
        // If no users exist, we can't create meetings
        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }
        
        $roomIds = MeetingRoom::pluck('id')->toArray();
        
        $meetingTypes = ['Regular', 'Board', 'Emergency', 'Strategy', 'Department', 'Project'];
        $statuses = ['scheduled', 'ongoing', 'completed', 'cancelled'];
        
        $meetings = [];
        
        // Create past meetings
        for ($i = 1; $i <= 10; $i++) {
            $startTime = Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $meetings[] = [
                'title' => $meetingTypes[array_rand($meetingTypes)] . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower($meetingTypes[array_rand($meetingTypes)]) . ' meeting for testing the system.',
                'meeting_type' => $meetingTypes[array_rand($meetingTypes)],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => $statuses[array_rand([2, 3])], // completed or cancelled
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        // Create upcoming meetings
        for ($i = 11; $i <= 20; $i++) {
            $startTime = Carbon::now()->addDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $meetings[] = [
                'title' => $meetingTypes[array_rand($meetingTypes)] . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower($meetingTypes[array_rand($meetingTypes)]) . ' meeting for testing the system.',
                'meeting_type' => $meetingTypes[array_rand($meetingTypes)],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => $statuses[array_rand([0, 1])], // scheduled or ongoing
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        // Create today's meetings
        for ($i = 21; $i <= 25; $i++) {
            $startTime = Carbon::today()->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $meetings[] = [
                'title' => $meetingTypes[array_rand($meetingTypes)] . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower($meetingTypes[array_rand($meetingTypes)]) . ' meeting for testing the system.',
                'meeting_type' => $meetingTypes[array_rand($meetingTypes)],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => Carbon::now()->between($startTime, $endTime) ? 'ongoing' : 'scheduled',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        Meeting::insert($meetings);
    }
}
