<?php

use App\Http\Middleware\Locale;
use App\Console\Commands\UnlockUser;
use Illuminate\Foundation\Application;
use App\Console\Commands\DatabaseSetUp;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\CheckPasswordExpiry;
use App\Http\Middleware\SecretCodeMiddleware;
use App\Console\Commands\FixStoragePermissions;
use App\Http\Middleware\CheckDynamicPermission;
use App\Console\Commands\MeetingReminderCommand;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckResourcePermissions;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        DatabaseSetUp::class,
        UnlockUser::class,
        MeetingReminderCommand::class,
        FixStoragePermissions::class,
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'check.permission' => CheckDynamicPermission::class,
            'resource.permission' => CheckResourcePermissions::class,
            'locale' => Locale::class,
            'secretCode' => SecretCodeMiddleware::class,
        ]);
        $middleware->web([
            CheckPasswordExpiry::class,
        ]);
        $middleware->api(prepend: [
            // EnsureTokenIsValid::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        //notifications
          // Daily 10 AM reminders for today's meetings
        $schedule->command('meetings:send-reminders --type=daily')->dailyAt('10:00')->withoutOverlapping()->appendOutputTo(storage_path('logs/meeting-reminders-daily.log'));
         // 2-hour reminders before meetings
        $schedule->command('meetings:send-reminders --type=twoHour')->everyFiveMinutes()->withoutOverlapping()->appendOutputTo(storage_path('logs/meeting-reminders-2hour.log'));
        // 30-minute reminders before meetings
        $schedule->command('meetings:send-reminders --type=halfHour')->everyFiveMinutes()->withoutOverlapping()->appendOutputTo(storage_path('logs/meeting-reminders-30min.log'));
        //permissions issues
        $schedule->command('storage:fix-permissions')->daily()->appendOutputTo(storage_path('logs/storage-permissions.log'));
    })
    ->create();
