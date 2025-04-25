<?php

namespace Modules\Settings\Services\Sms;


use Illuminate\Support\Facades\Http;
use Modules\Settings\Models\SmsConfiguration;

class SparrowSmsService implements SmsServiceInterface
{
    protected $config;
    
    public function __construct()
    {
        $this->loadConfiguration();
    }
    
    /**
     * Load Sparrow SMS configuration from database
     */
    protected function loadConfiguration()
    {
        $this->config = SmsConfiguration::where('is_active', true)
            ->whereHas('provider', function($query) {
                $query->where('code', 'sparrow');
            })
            ->latest()
            ->first();
            
        if (!$this->config) {
            throw new \Exception('Sparrow SMS configuration not found or not active');
        }
    }
    
    /**
     * Send SMS using Sparrow SMS
     * 
     * @param string $to
     * @param string $message
     * @return array
     */
    public function send(string $to, string $message): array
    {
        // dd($this->config->base_url, $this->config->api_token, $this->config->sender_id, $to, $message);
        try {
            $response = Http::get($this->config->base_url, [
                'token' => $this->config->api_token,
                'from'  => $this->config->sender_id,
                'to'    => $to,
                'text'  => $message
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'message' => $response->successful() ? 'SMS sent successfully' : 'Failed to send SMS'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Send bulk SMS using Sparrow SMS
     * 
     * @param array $recipients
     * @param string $message
     * @return array
     */
    public function sendBulk(array $recipients, string $message): array
    {
        try {
            $numbers = implode(',', $recipients);
            
            $response = Http::get($this->config->base_url, [
                'token' => $this->config->api_token,
                'from'  => $this->config->sender_id,
                'to'    => $numbers,
                'text'  => $message
            ]);
            
            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'message' => $response->successful() ? 'Bulk SMS sent successfully' : 'Failed to send bulk SMS'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}