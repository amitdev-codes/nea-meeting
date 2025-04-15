<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;

class ActionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $meetingIds = Meeting::pluck('id')->toArray();
        if (empty($meetingIds)) {
            $this->command->error('No meetings found in the database. Please seed meetings first.');
            return;
        }
        
        $userIds = DB::table('users')->pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found in the database. Please seed users first.');
            return;
        }
        
        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'in_progress', 'completed', 'deferred'];
        
        $actionItems = [];
        
        foreach ($meetingIds as $meetingId) {
            $meeting = Meeting::find($meetingId);
            
            // Generate 2-5 action items per meeting
            $itemCount = rand(2, 5);
            
            for ($i = 0; $i < $itemCount; $i++) {
                $priority = $priorities[array_rand($priorities)];
                $status = $statuses[array_rand($statuses)];
                $dueDate = Carbon::parse($meeting->end_time)->addDays(rand(3, 30));
                
                $actionItems[] = [
                    'meeting_id' => $meetingId,
                    'description' => $this->getRandomActionItemDescription(),
                    'assigned_to' => $userIds[array_rand($userIds)],
                    'due_date' => $dueDate,
                    'priority' => $priority,
                    'status' => $status,
                    'completed_at' => $status === 'completed' ? $dueDate->subDays(rand(0, 3)) : null,
                    'notes' => rand(0, 1) ? $this->getRandomActionItemNote() : null,
                    'created_at' => Carbon::parse($meeting->end_time),
                    'updated_at' => Carbon::parse($meeting->end_time)->addDays(rand(1, 5)),
                ];
            }
        }
        
        ActionItem::insert($actionItems);
    }
    
    private function getRandomActionItemDescription()
    {
        $descriptions = [
            'Prepare financial report for department review',
            'Schedule follow-up meeting with procurement team',
            'Draft project proposal for new substation',
            'Review equipment maintenance schedules',
            'Update department policies according to new regulations',
            'Contact vendors for quotations on new equipment',
            'Research alternative energy solutions for remote areas',
            'Prepare training materials for new system implementation',
            'Compile monthly performance statistics',
            'Coordinate with IT for software updates',
            'Submit budget revision proposal',
            'Finalize resource allocation for Q3',
            'Prepare presentation for board meeting',
            'Conduct risk assessment for new project',
            'Review and approve staff leave requests',
            'Develop mitigation plan for identified issues',
            'Update project timeline based on recent developments',
            'Coordinate with communications team for public announcement',
            'Prepare monthly newsletter content',
            'Conduct site visit and prepare inspection report'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }
    
    private function getRandomActionItemNote()
    {
        $notes = [
            'Priority may change depending on board decision',
            'Coordinate with finance department before proceeding',
            'May require additional resources',
            'Previously delayed due to budget constraints',
            'Consider outsourcing if internal resources unavailable',
            'Follow standard reporting template',
            'Involves coordination with multiple departments',
            'Reference previous reports for context',
            'Potentially sensitive information - handle with care',
            'May be delegated with appropriate supervision'
        ];
        
        return $notes[array_rand($notes)];
    }
}
