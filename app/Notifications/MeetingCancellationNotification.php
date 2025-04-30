<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MeetingCancellationNotification extends Notification
{
    

    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // Format dates for display
        $meetingDate = $this->meeting->meeting_date_ad ?? $this->meeting->meeting_date;
        $startTime = Carbon::parse($this->meeting->start_time)->format('h:i A');
        $endTime = Carbon::parse($this->meeting->end_time)->format('h:i A');

        return (new MailMessage)
            ->subject('NEA Meeting Cancelled: ' . $this->meeting->title)
            ->markdown('neameeting::emails.meetings.cancellation', [
                'meeting' => $this->meeting,
                'user' => $notifiable,
                'meetingDate' => $meetingDate,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'status' => 'Cancelled',
            'meeting_date' => $this->meeting->meeting_date,
            'start_time' => $this->meeting->start_time,
            'end_time' => $this->meeting->end_time,
        ];
    }
}