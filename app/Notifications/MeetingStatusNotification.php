<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MeetingStatusNotification extends Notification
{

    protected $meeting;
    protected $isExternal;
    protected $notificationType;

    /**
     * Constructor to initialize the meeting, recipient type, and notification type.
     *
     * @param Meeting $meeting
     * @param string $notificationType Type of notification: 'rescheduled', 'cancellation', or 'reminder'
     * @param bool $isExternal Whether the notification is for an external contact
     */
    public function __construct(Meeting $meeting, string $notificationType, bool $isExternal = false)
    {
        $this->meeting = $meeting;
        $this->notificationType = $notificationType;
        $this->isExternal = $isExternal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->isExternal ? ['mail'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $meetingDate = $this->meeting->meeting_date ?? $this->meeting->meeting_date_ad;
        $startTime = Carbon::parse($this->meeting->start_time)->format('h:i A');
        $endTime = Carbon::parse($this->meeting->end_time)->format('h:i A');

        // Prepare user data based on recipient type
        $user = $this->isExternal
            ? (object) [
                'username' => $notifiable->name ?? 'Guest',
                'email' => $notifiable->email,
            ]
            : $notifiable;

        // Determine subject and template based on notification type
        $subject = $this->getSubject();
        $template = $this->getTemplate();

        return (new MailMessage)
            ->subject($subject)
            ->markdown($template, [
                'meeting' => $this->meeting,
                'user' => $user,
                'meetingDate' => $meetingDate,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'url' => route('admin.landingPage.view', $this->meeting->id),
            ]);
    }

    /**
     * Get the subject based on notification type.
     *
     * @return string
     */
    protected function getSubject()
    {
        switch ($this->notificationType) {
            case 'scheduled':
                return 'NEA Meeting Scheduled: ' . $this->meeting->title;
            case 'rescheduled':
                return 'NEA Meeting Rescheduled: ' . $this->meeting->title;
            case 'cancellation':
                return 'NEA Meeting Cancelled: ' . $this->meeting->title;
            case 'reminder':
                return 'NEA Meeting Reminder: ' . $this->meeting->title;
            default:
                throw new \InvalidArgumentException("Invalid notification type: {$this->notificationType}");
        }
    }

    /**
     * Get the email template based on notification type.
     *
     * @return string
     */
    protected function getTemplate()
    {
        switch ($this->notificationType) {
            case 'scheduled':
                return 'neameeting::emails.meetings.scheduled';
            case 'rescheduled':
                return 'neameeting::emails.meetings.rescheduled';
            case 'cancellation':
                return 'neameeting::emails.meetings.cancellation';
            case 'reminder':
                return 'neameeting::emails.meetings.reminder';
            default:
                throw new \InvalidArgumentException("Invalid notification type: {$this->notificationType}");
        }
    }

    /**
     * Get the array representation of the notification (for database storage).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'meeting_date' => $this->meeting->meeting_date,
            'start_time' => $this->meeting->start_time,
            'end_time' => $this->meeting->end_time,
            'notification_type' => $this->notificationType,
        ];
    }
}