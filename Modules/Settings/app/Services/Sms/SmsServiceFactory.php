<?php

namespace Modules\Settings\Services\Sms;

use Modules\Settings\Models\SmsConfiguration;
use Modules\Settings\Services\Sms\GenericSmsService;
use Modules\Settings\Services\Sms\SparrowSmsService;



class SmsServiceFactory
{
    /**
     * Create a new SMS service based on the active configuration
     * 
     * @return \App\Services\Sms\SmsServiceInterface
     * @throws \Exception
     */
    public static function create()
    {
        $activeConfig = SmsConfiguration::where('is_active', true)
            ->with('provider')
            ->latest()
            ->first();
        
            if (!$activeConfig) {
                \Log::warning('No active SMS configuration found, returning null');
                return null; // Return null to skip SMS service initialization
            }
        
        return match ($activeConfig->provider->code) {
            'sparrow' => new SparrowSmsService(),
            'generic' => new GenericSmsService(),
            default => throw new \Exception('Unsupported SMS provider: ' . $activeConfig->provider->code),
        };
    }
}