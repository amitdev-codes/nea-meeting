<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use App\Enums\MeetingType;
use App\Enums\MeetingStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Helpers\NepaliDateConverter;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingRoom;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all user IDs
        $userIds = DB::table('users')->pluck('id')->toArray();
        
        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }
        
        $roomIds = MeetingRoom::pluck('id')->toArray();
        
        $meetings = [];
        
        // Create past meetings
        for ($i = 1; $i <= 10; $i++) {
            $startTime = Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $nepaliDate = NepaliDateConverter::toNepaliDate($startTime);
            $meetingDate = sprintf('%s-%s-%s', 
                $nepaliDate['year'], 
                str_pad($nepaliDate['month'], 2, '0', STR_PAD_LEFT), 
                str_pad($nepaliDate['day'], 2, '0', STR_PAD_LEFT)
            );
            
            $meetings[] = [
                'title' => MeetingType::cases()[array_rand(MeetingType::cases())]->value . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower(MeetingType::cases()[array_rand(MeetingType::cases())]->name) . ' meeting for testing the system.',
                'meeting_type' => MeetingType::cases()[array_rand(MeetingType::cases())]->value,
                'meeting_date' => $meetingDate,
                'meeting_date_ad' => $startTime->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => rand(0, 1) ? MeetingStatus::Completed->value : MeetingStatus::Cancelled->value,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        // Create upcoming meetings
        for ($i = 11; $i <= 20; $i++) {
            $startTime = Carbon::now()->addDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $nepaliDate = NepaliDateConverter::toNepaliDate($startTime);
            $meetingDate = sprintf('%s-%s-%s', 
                $nepaliDate['year'], 
                str_pad($nepaliDate['month'], 2, '0', STR_PAD_LEFT), 
                str_pad($nepaliDate['day'], 2, '0', STR_PAD_LEFT)
            );
            
            $meetings[] = [
                'title' => MeetingType::cases()[array_rand(MeetingType::cases())]->value . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower(MeetingType::cases()[array_rand(MeetingType::cases())]->name) . ' meeting for testing the system.',
                'meeting_type' => MeetingType::cases()[array_rand(MeetingType::cases())]->value,
                'meeting_date' => $meetingDate,
                'meeting_date_ad' => $startTime->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => rand(0, 1) ? MeetingStatus::Scheduled->value : MeetingStatus::Ongoing->value,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        // Create today's meetings
        for ($i = 21; $i <= 25; $i++) {
            $startTime = Carbon::today()->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));
            
            $nepaliDate = NepaliDateConverter::toNepaliDate($startTime);
            $meetingDate = sprintf('%s-%s-%s', 
                $nepaliDate['year'], 
                str_pad($nepaliDate['month'], 2, '0', STR_PAD_LEFT), 
                str_pad($nepaliDate['day'], 2, '0', STR_PAD_LEFT)
            );
            
            $meetings[] = [
                'title' => MeetingType::cases()[array_rand(MeetingType::cases())]->value . ' Meeting #' . $i,
                'description' => 'This is a sample ' . strtolower(MeetingType::cases()[array_rand(MeetingType::cases())]->name) . ' meeting for testing the system.',
                'meeting_type' => MeetingType::cases()[array_rand(MeetingType::cases())]->value,
                'meeting_date' => $meetingDate,
                'meeting_date_ad' => $startTime->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'meeting_room_id' => $roomIds[array_rand($roomIds)],
                'is_virtual' => rand(0, 1),
                'virtual_meeting_link' => 'https://zoom.us/j/' . rand(1000000000, 9999999999),
                'status' => Carbon::now()->between($startTime, $endTime) ? MeetingStatus::Ongoing->value : MeetingStatus::Scheduled->value,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
            ];
        }
        
        Meeting::insert($meetings);
    }
}
