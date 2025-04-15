<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForcePasswordChangecontroller extends Controller
{
    public function show()
    {
        if (!session()->has('force_password_change')) {
            return redirect()->route('dashboard');
        }

        return view('pages.auth.passwords.force-password-change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $request->user()->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'force_password_change' => false,
        ]);

        session()->forget('force_password_change');
        return redirect()->route('dashboard')->with('status', 'Password changed successfully.');
    }
}
