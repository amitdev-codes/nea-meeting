<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\NeaMeeting\Database\Seeders\MeetingSeeder;
use Modules\NeaMeeting\Database\Seeders\ActionItemSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingRoomSeeder;
use Modules\NeaMeeting\Database\Seeders\NotificationSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingMinuteSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingAttendeeSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingDocumentSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingReminderSeeder;
use Modules\NeaMeeting\Database\Seeders\MeetingRecurrenceSeeder;

class NeaMeetingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            MeetingRoomSeeder::class,
            MeetingSeeder::class,
            // MeetingAttendeeSeeder::class
        ]);
    }
}
