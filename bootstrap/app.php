<?php

use App\Console\Commands\DatabaseSetUp;
use App\Console\Commands\UnlockUser;
use App\Http\Middleware\CheckDynamicPermission;
use App\Http\Middleware\CheckPasswordExpiry;
use App\Http\Middleware\CheckResourcePermissions;
use App\Http\Middleware\Locale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
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
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'check.permission' => CheckDynamicPermission::class,
            'resource.permission' => CheckResourcePermissions::class,
            'locale' => Locale::class,
        ]);
        $middleware->web([
            CheckPasswordExpiry::class,
        ]);
        $middleware->api(prepend: [
            EnsureTokenIsValid::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
