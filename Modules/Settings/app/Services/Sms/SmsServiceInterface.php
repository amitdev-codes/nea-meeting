<?php

namespace Modules\Settings\Services\Sms;
interface SmsServiceInterface
{
    /**
     * Send SMS to a recipient
     * 
     * @param string $to
     * @param string $message
     * @return array
     */
    public function send(string $to, string $message): array;
    
    /**
     * Send bulk SMS to multiple recipients
     * 
     * @param array $recipients
     * @param string $message
     * @return array
     */
    public function sendBulk(array $recipients, string $message): array;
}
