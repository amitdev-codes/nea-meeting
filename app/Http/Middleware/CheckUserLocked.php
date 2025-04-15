<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLocked
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is locked
        if ($user && $user->is_locked) {
            Auth::logout(); // Log out the user

            return redirect()->route('login')->with('error', 'Your account is locked due to too many failed login attempts. Please contact support.');
        }

        return $next($request);
    }
}
