<?php

namespace Modules\Settings\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Modules\Settings\Models\EmailConfiguration;

class DynamicEmailService
{
    protected $emailConfig;

    public function __construct()
    {
        $this->loadActiveEmailConfiguration();
    }

    /**
     * Load the active email configuration from the database
     */
    protected function loadActiveEmailConfiguration()
    {
        $this->emailConfig = EmailConfiguration::where('is_active', true)
            ->latest()
            ->first();

        if ($this->emailConfig) {
            $this->setMailConfig();
        }
    }

    /**
     * Set the mail configuration dynamically
     */
    protected function setMailConfig()
    {
        $config = [
            'transport'     => $this->emailConfig->mail_mailer,
            'host'       => $this->emailConfig->mail_host,
            'port'       => $this->emailConfig->mail_port,
            'username'   => $this->emailConfig->mail_username,
            'password'   => $this->emailConfig->mail_password,
            'encryption' => $this->emailConfig->mail_encryption,
            'from'       => [
                'address' => $this->emailConfig->mail_from_address,
                'name'    => $this->emailConfig->mail_from_name,
            ],
        ];

        // Update the mail configuration
        Config::set('mail.mailers.smtp', $config);
        Config::set('mail.from', $config['from']);
    }

    /**
     * Send email using the active configuration
     * 
     * @param string|array $to
     * @param \Illuminate\Mail\Mailable $mailable
     * @return void
     */
    public function send($to, $mailable)
    {
        if (!$this->emailConfig) {
            throw new \Exception('No active email configuration found');
        }

        Mail::to($to)->send($mailable);
    }
}