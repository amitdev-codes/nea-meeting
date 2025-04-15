<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlayersRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $playerName;

    public $playerEmail;

    public $playerSpeciality;

    public $playerMobile;

    // public $playerPosition;

    // public $registrationId;

    // public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($playerDetails)
    {
        $this->playerName = $playerDetails->first_name . ' ' . $playerDetails->last_name;
        $this->playerEmail = $playerDetails->email;
        $this->playerSpeciality = $playerDetails->speciality;
        $this->playerMobile = $playerDetails->mobile;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sudurpaschim Royals - Player Registration Submitted Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.player-registration',
            with: [
                'playerName' => $this->playerName,
                // 'playerPosition' => $this->playerPosition,
                // 'registrationId' => $this->registrationId,
                // 'verificationUrl' => $this->verificationUrl,
                'playerEmail' => $this->playerEmail,
                'playerSpeciality' => $this->playerSpeciality,
                'playerMobile' => $this->playerMobile,
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
