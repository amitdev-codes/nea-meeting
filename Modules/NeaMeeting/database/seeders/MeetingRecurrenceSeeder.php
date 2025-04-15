<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingRecurrence;

class MeetingRecurrenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Select a few meetings to make them recurring (about 30% of all meetings)
        $meetingIds = Meeting::pluck('id')->toArray();
        $recurringMeetingCount = (int) ceil(count($meetingIds) * 0.3);
        
        if ($recurringMeetingCount == 0) {
            $this->command->info('No meetings found. Skipping recurrence seeding.');
            return;
        }
        
        // Randomly select meetings to make recurring
        shuffle($meetingIds);
        $recurringMeetingIds = array_slice($meetingIds, 0, $recurringMeetingCount);
        
        $recurrencePatterns = ['daily', 'weekly', 'bi-weekly', 'monthly', 'custom'];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        $recurrences = [];
        
        foreach ($recurringMeetingIds as $meetingId) {
            $meeting = Meeting::find($meetingId);
            $pattern = $recurrencePatterns[array_rand($recurrencePatterns)];
            
            $recurrence = [
                'meeting_id' => $meetingId,
                'recurrence_pattern' => $pattern,
                'recurrence_interval' => 1,
                'days_of_week' => null,
                'day_of_month' => null,
                'end_date' => Carbon::parse($meeting->start_time)->addMonths(rand(3, 12)),
                'occurrences' => null,
                'created_at' => Carbon::parse($meeting->created_at),
                'updated_at' => Carbon::parse($meeting->created_at),
            ];
            
            // Set pattern-specific fields
            switch ($pattern) {
                case 'weekly':
                    // Get the day of week from the meeting's start date
                    $recurrence['days_of_week'] = Carbon::parse($meeting->start_time)->format('l');
                    break;
                    
                case 'bi-weekly':
                    $recurrence['recurrence_interval'] = 2;
                    $recurrence['days_of_week'] = Carbon::parse($meeting->start_time)->format('l');
                    break;
                    
                case 'monthly':
                    // Day of month
                    $recurrence['day_of_month'] = Carbon::parse($meeting->start_time)->day;
                    break;
                    
                case 'custom':
                    // Pick random day(s) of week
                    shuffle($daysOfWeek);
                    $recurrence['days_of_week'] = implode(',', array_slice($daysOfWeek, 0, rand(1, 3)));
                    $recurrence['recurrence_interval'] = rand(1, 4);
                    break;
            }
            
            $recurrences[] = $recurrence;
        }
        
        MeetingRecurrence::insert($recurrences);
    }
}
