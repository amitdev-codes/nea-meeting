<?php

namespace Modules\Settings\Models;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\EmailConfigurationFactory;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'mail_port' => 'integer',
    ];
    public function sendTestEmail($to, $subject, $message)
    {
        // Temporarily override mail configuration with model values
        config([
            'mail.mailer' => $this->mail_mailer,
            'mail.host' => $this->mail_host,
            'mail.port' => $this->mail_port,
            'mail.username' => $this->mail_username,
            'mail.password' => $this->mail_password,
            'mail.encryption' => $this->mail_encryption,
            'mail.from.address' => $this->mail_from_address,
            'mail.from.name' => $this->mail_from_name,
        ]);

        // Send the email
        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)->subject($subject);
        });
    }
}
