<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlayerRegistrationManagementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $playerData;
    public $playerName;
    public $playerSpeciality;

    /**
     * Create a new message instance.
     */
    public function __construct($playerDetails)
    {
        $this->playerData = $playerDetails;

        $this->playerName = $playerDetails->first_name . ' ' . $playerDetails->last_name;

        $this->playerSpeciality = $playerDetails->speciality;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Player Registration - '.$this->playerName . '('.$this->playerSpeciality.')',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.player-registration-management',
            with: [
                'playerName' => $this->playerName,
                'playerDOB' => $this->playerData->date_of_birth,
                'playerGender' => $this->playerData->gender,
                'playerEmail' => $this->playerData->email,
                'playerMobile' => $this->playerData->mobile,
                'playerAddress' => $this->playerData->address,

                'playerSpeciality' => $this->playerData->speciality,
                'playerBattingStyle' => $this->playerData->batting_style,
                'playerBowlingStyle' => $this->playerData->bowling_style,

                'playerTeam' => $this->playerData->team,
                'playerPosition' => $this->playerData->position,
                'playerDistrict' => $this->playerData->district,
                'playerProvience' => $this->playerData->provience,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
