<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\MeetingMinute;
use Modules\NeaMeeting\Models\MeetingAttendee;

class MeetingMinuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $completedMeetingIds = Meeting::where('status', 'completed')->pluck('id')->toArray();
        if (empty($completedMeetingIds)) {
            $this->command->info('No completed meetings found. Skipping minutes seeding.');
            return;
        }
        
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }
        
        $minutes = [];
        
        foreach ($completedMeetingIds as $meetingId) {
            $meeting = Meeting::find($meetingId);
            $recordedBy = $userIds[array_rand($userIds)];
            $isApproved = rand(0, 1) == 1;
            
            $minutes[] = [
                'meeting_id' => $meetingId,
                'content' => $this->generateSampleMinutes($meeting),
                'recorded_by' => $recordedBy,
                'approved' => $isApproved,
                'approved_by' => $isApproved ? $userIds[array_rand($userIds)] : null,
                'approved_at' => $isApproved ? Carbon::parse($meeting->end_time)->addDays(rand(1, 5)) : null,
                'created_at' => Carbon::parse($meeting->end_time)->addHours(rand(1, 24)),
                'updated_at' => Carbon::parse($meeting->end_time)->addHours(rand(24, 72)),
            ];
        }
        
        MeetingMinute::insert($minutes);
    }
    
    private function generateSampleMinutes($meeting)
    {
        $attendees = MeetingAttendee::where('meeting_id', $meeting->id)
            ->where('attendance_status', 'attended')
            ->with('user')
            ->get();
        
        $minutes = "# Minutes of Meeting: {$meeting->title}\n\n";
        $minutes .= "**Date:** " . Carbon::parse($meeting->start_time)->format('F j, Y') . "\n";
        $minutes .= "**Time:** " . Carbon::parse($meeting->start_time)->format('g:i A') . " - " . Carbon::parse($meeting->end_time)->format('g:i A') . "\n";
        $minutes .= "**Location:** " . ($meeting->is_virtual ? "Virtual Meeting" : "Room: " . optional($meeting->meetingRoom)->name) . "\n\n";
        
        $minutes .= "## Attendees\n";
        if ($attendees->count() > 0) {
            foreach ($attendees as $attendee) {
                if (isset($attendee->user)) {
                    $minutes .= "- {$attendee->user->first_name} {$attendee->user->last_name} ({$attendee->user->position})\n";
                }
            }
        } else {
            $minutes .= "- [Attendance information not available]\n";
        }
        
        $minutes .= "\n## Agenda\n";
        $minutes .= "1. Welcome and Introduction\n";
        $minutes .= "2. Review of Previous Meeting Minutes\n";
        $minutes .= "3. Project Updates\n";
        $minutes .= "4. Discussion Items\n";
        $minutes .= "5. Action Items\n";
        $minutes .= "6. Any Other Business\n\n";
        
        $minutes .= "## Discussion Points\n\n";
        $minutes .= "### 1. Welcome and Introduction\n";
        $minutes .= "The meeting was called to order at " . Carbon::parse($meeting->start_time)->format('g:i A') . ".\n\n";
        
        $minutes .= "### 2. Review of Previous Meeting Minutes\n";
        $minutes .= "The minutes from the previous meeting were reviewed and approved without changes.\n\n";
        
        $minutes .= "### 3. Project Updates\n";
        $minutes .= "- Project A is progressing as scheduled. The team reported successful completion of Phase 1.\n";
        $minutes .= "- Project B has encountered some delays due to equipment shortages. A revised timeline will be submitted next week.\n";
        $minutes .= "- Project C was approved for additional funding to accommodate the expanded scope.\n\n";
        
        $minutes .= "### 4. Discussion Items\n";
        $minutes .= "- The committee discussed the upcoming budget allocation for Q3.\n";
        $minutes .= "- Infrastructure improvements in the eastern region were evaluated.\n";
        $minutes .= "- New safety protocols were introduced and will be implemented starting next month.\n\n";
        
        $minutes .= "### 5. Action Items\n";
        $minutes .= "- Department heads to submit Q3 budget proposals by next Friday.\n";
        $minutes .= "- HR to finalize the training schedule for new safety protocols.\n";
        $minutes .= "- IT team to complete system upgrades by end of month.\n\n";
        
        $minutes .= "### 6. Any Other Business\n";
        $minutes .= "- The annual staff retreat was confirmed for October 15-17.\n";
        $minutes .= "- Next meeting scheduled for " . Carbon::parse($meeting->end_time)->addDays(14)->format('F j, Y') . ".\n\n";
        
        $minutes .= "The meeting was adjourned at " . Carbon::parse($meeting->end_time)->format('g:i A') . ".";
        
        return $minutes;
    }
}
