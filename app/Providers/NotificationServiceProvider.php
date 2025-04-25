<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Settings\Services\DynamicEmailService;
use Modules\Settings\Services\Sms\SmsServiceFactory;
use Modules\Settings\Services\Sms\SmsServiceInterface;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(DynamicEmailService::class, function ($app) {
            return new DynamicEmailService();
        });
        
        $this->app->singleton(SmsServiceInterface::class, function ($app) {
            return SmsServiceFactory::create();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
