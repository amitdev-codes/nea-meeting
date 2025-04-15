<?php

namespace App\Providers;

use App\Events\MeetingCreated;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendMeetingNotification;
use App\Listeners\UpdateLastLoginTimestamp;
use App\Listeners\UpdateLastLogoutTimestamp;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        Login::class => [
            UpdateLastLoginTimestamp::class,
        ],
        Logout::class => [
            UpdateLastLogoutTimestamp::class,
        ],
        MeetingCreated::class => [
           SendMeetingNotification::class,
        ],
    ];
}
