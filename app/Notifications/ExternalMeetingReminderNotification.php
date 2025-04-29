<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ExternalMeetingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $meeting;

    /**
     * Create a new notification instance.
     */
    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $formattedDateTime = Carbon::parse($this->meeting->scheduled_at)->format('M d, Y \a\t h:i A');

        return (new MailMessage)
            ->subject('Reminder: Upcoming Meeting - ' . $this->meeting->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that you have a meeting scheduled in 2 hours:')
            ->line('**Meeting:** ' . $this->meeting->title)
            ->line('**Date & Time:** ' . $formattedDateTime)
            ->when(!empty($this->meeting->location), function ($message) {
                return $message->line('**Location:** ' . $this->meeting->location);
            })
            ->when(!empty($this->meeting->agenda), function ($message) {
                return $message->line('**Agenda:** ' . $this->meeting->agenda);
            })
            ->when(!empty($this->meeting->meeting_link), function ($message) {
                return $message->line('**Meeting Link:** ' . $this->meeting->meeting_link);
            })
            ->line('Please ensure you are prepared for this meeting.')
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'scheduled_at' => $this->meeting->scheduled_at,
            'notification_type' => 'reminder',
        ];
    }
}