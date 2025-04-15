<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $siteSettings=Cache::get('site_settings');
        $force_password_change_days=$siteSettings['force_password_change_days']??90;
        if (auth()->check()) {
            $user = auth()->user();
            if ($request->is('password/change*') || $request->is('password/force-change*')) {
                return $next($request);
            }

            // dd($force_password_change_days);
            $days=(int)$force_password_change_days;

            // Check for first login
            if (is_null($user->password_changed_at)) {
                session()->put('force_password_change', 'Please change your password for first time login.');
                return redirect()->route('password.force-change');
            }
            // Check for password expiry
            if (Carbon::parse($user->password_changed_at)->addDays($days)->isPast()) {
                session()->put('force_password_change', 'Your password has expired. Please set a new password.');
                return redirect()->route('password.force-change');
            }
        }

        return $next($request);
    }
}
