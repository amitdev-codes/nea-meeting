<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Modules\NeaMeeting\Models\Meeting;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;
    public $user;
    public $reason;

    public function __construct(Meeting $meeting, User $user, string $reason = '')
    {
        $this->meeting = $meeting;
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        // dd($this->meeting);
        return $this->subject('NEA Meeting Cancellation: ' . $this->meeting->title)
            ->view('neameeting::emails.meetings.meeting_cancelled')
            ->with([
                'meetingDate' => $this->meeting->meeting_date,
                'startTime' => $this->meeting->start_time,
                'endTime' => $this->meeting->end_time ?? 'N/A',
            ]);
    }
}
