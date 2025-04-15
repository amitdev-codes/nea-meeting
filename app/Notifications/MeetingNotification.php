<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MeetingNotification extends Notification
{
    use Queueable;

    private $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Send via email and store in database
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Meeting Scheduled')
                    ->line('A new meeting has been scheduled.')
                    ->line('Title: ' . $this->meeting->title)
                    ->line('Date: ' . $this->meeting->date)
                    ->line('Time: ' . $this->meeting->time)
                    ->action('View Meeting', url('/meetings/' . $this->meeting->id))
                    ->line('Thank you for your attention!');
    }

    public function toArray($notifiable)
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'date' => $this->meeting->date,
            'time' => $this->meeting->time,
        ];
    }
}
