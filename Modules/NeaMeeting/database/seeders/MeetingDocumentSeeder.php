<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;

class MeetingDocumentSeeder extends Seeder
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
        
        $documentTypes = ['agenda', 'presentation', 'report', 'minutes', 'proposal', 'financial_report'];
        $documents = [];
        
        foreach ($meetingIds as $meetingId) {
            // Each meeting will have 1-4 documents
            $docCount = rand(1, 4);
            
            // Always create an agenda
            $documents[] = [
                'meeting_id' => $meetingId,
                'title' => 'Meeting Agenda',
                'document_type' => 'agenda',
                'file_path' => 'documents/meeting_' . $meetingId . '/agenda.pdf',
                'uploaded_by' => Meeting::find($meetingId)->created_by,
                'version' => '1.0',
                'description' => 'Agenda for the meeting',
                'created_at' => now()->subDays(rand(5, 15)),
                'updated_at' => now()->subDays(rand(3, 5)),
            ];
            
            // Add additional random documents
            for ($i = 1; $i < $docCount; $i++) {
                $docType = $documentTypes[array_rand(array_diff($documentTypes, ['agenda']))];
                $uploaderId = $userIds[array_rand($userIds)];
                
                $documents[] = [
                    'meeting_id' => $meetingId,
                    'title' => ucfirst(str_replace('_', ' ', $docType)) . ' - Meeting ' . $meetingId,
                    'document_type' => $docType,
                    'file_path' => 'documents/meeting_' . $meetingId . '/' . $docType . '_' . rand(1000, 9999) . '.pdf',
                    'uploaded_by' => $uploaderId,
                    'version' => '1.0',
                    'description' => 'Sample ' . str_replace('_', ' ', $docType) . ' document',
                    'created_at' => now()->subDays(rand(5, 10)),
                    'updated_at' => now()->subDays(rand(1, 4)),
                ];
            }
        }
        
        MeetingDocument::insert($documents);
    }
}
