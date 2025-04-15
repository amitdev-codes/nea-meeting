<?php

namespace Modules\NeaMeeting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\NeaMeeting\Models\Meeting;
use Modules\NeaMeeting\Models\Notification;

class NotificationSeeder extends Seeder
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
        
        $notificationTypes = [
            'meeting_invite',
            'meeting_reminder',
            'meeting_update',
            'meeting_cancellation',
            'action_item_assigned',
            'meeting_minutes_available',
            'document_uploaded'
        ];
        
        $notifications = [];
        $notificationCount = min(100, count($userIds) * 5); // Limit to a reasonable number
        
        for ($i = 0; $i < $notificationCount; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $meetingId = $meetingIds[array_rand($meetingIds)];
            $meeting = Meeting::find($meetingId);
            $notificationType = $notificationTypes[array_rand($notificationTypes)];
            
            $notifications[] = [
                'user_id' => $userId,
                'meeting_id' => $meetingId,
                'notification_type' => $notificationType,
                'message' => $this->generateNotificationMessage($notificationType, $meeting),
                'is_read' => rand(0, 1),
                  'created_at' => (clone $startTime)->subDays(rand(3, 10)),
                'updated_at' => (clone $startTime)->subDays(rand(1, 3)),
                    ];
        }
                Notification::insert($notifications);
    }
}
