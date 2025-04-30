<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExternalMeetingCancellationNotification extends Notification 
{


    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Only send emails to external contacts
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $meetingDate = $this->meeting->meeting_date_ad ?? $this->meeting->meeting_date;
        $startTime = Carbon::parse($this->meeting->start_time)->format('h:i A');
        $endTime = Carbon::parse($this->meeting->end_time)->format('h:i A');

        return (new MailMessage)
            ->subject('NEA Meeting Cancelled: ' . $this->meeting->title)
            ->markdown('neameeting::emails.meetings.cancellation_external', [
                'meeting' => $this->meeting,
                'user' => (object) [
                    'username' => $notifiable->name ?? 'Guest',
                    'email' => $notifiable->email,
                ],
                'meetingDate' => $meetingDate,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ]);
    }

    /**
     * Get the array representation of the notification (optional, not used for external users).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'status' => 'cancelled',
            'meeting_date' => $this->meeting->meeting_date,
            'start_time' => $this->meeting->start_time,
            'end_time' => $this->meeting->end_time,
        ];
    }
}