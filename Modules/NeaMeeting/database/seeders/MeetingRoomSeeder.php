<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\NeaMeeting\Models\MeetingRoom;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $meetingRooms = [
            // [
            //     'name' => 'NEA Board Room',
            //     'location' => 'Head Office, 1st Floor',
            //     'capacity' => 20,
            //     'has_projector' => true,
            //     'has_video_conference' => true,
            //     'notes' => 'Main board room with full A/V capabilities',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Conference Room A',
            //     'location' => 'Head Office, 2nd Floor',
            //     'capacity' => 12,
            //     'has_projector' => true,
            //     'has_video_conference' => false,
            //     'notes' => 'Mid-sized meeting room with projector',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Conference Room B',
            //     'location' => 'Head Office, 2nd Floor',
            //     'capacity' => 12,
            //     'has_projector' => true,
            //     'has_video_conference' => false,
            //     'notes' => 'Mid-sized meeting room with projector',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Small Meeting Room 1',
            //     'location' => 'Head Office, 3rd Floor',
            //     'capacity' => 6,
            //     'has_projector' => false,
            //     'has_video_conference' => false,
            //     'notes' => 'Small room for team meetings',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Engineering Conference Room',
            //     'location' => 'Technical Building, 1st Floor',
            //     'capacity' => 15,
            //     'has_projector' => true,
            //     'has_video_conference' => true,
            //     'notes' => 'Dedicated room for engineering team with technical equipment',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'name' => 'Training Room',
            //     'location' => 'Training Center Building',
            //     'capacity' => 30,
            //     'has_projector' => true,
            //     'has_video_conference' => true,
            //     'notes' => 'Large room for trainings and workshops',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],

            [
                'name' => 'Md Meeting Room',
                'location' => 'Head Office, 2nd Floor',
                'capacity' => 15,
                'has_projector' => true,
                'has_video_conference' => true,
                'notes' => 'Executive level meeting room with modern A/V equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meeting Hall Top Floor',
                'location' => 'Head Office, Top Floor',
                'capacity' => 50,
                'has_projector' => true,
                'has_video_conference' => true,
                'notes' => 'Large hall suitable for presentations and seminars',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meeting Hall Ground Floor',
                'location' => 'Head Office, Ground Floor',
                'capacity' => 40,
                'has_projector' => false,
                'has_video_conference' => false,
                'notes' => 'Spacious hall without A/V setup',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Other',
                'location' => 'Various Locations',
                'capacity' => 10,
                'has_projector' => false,
                'has_video_conference' => false,
                'notes' => 'Flexible use meeting space for small gatherings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        MeetingRoom::insert($meetingRooms);
    }
}
