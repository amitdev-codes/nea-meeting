<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'failed_attempts' => 'required|integer|min:1',
            'force_password_change_days' => 'required|integer|min:1',
        ]);

        // Save settings to the database or config file
        $settings = SiteSetting::firstOrNew();
        $settings->settings = [
            'failed_attempts' => $request->failed_attempts,
            'force_password_change_days' => $request->force_password_change_days,
        ];
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'user_identifier' => 'required|string',
        ]);

        $user = User::where('email', $request->user_identifier)
            ->orWhere('username', $request->user_identifier)
            ->orWhere('mobile_no', $request->user_identifier)
            ->first();

        if ($user) {
            $user->update([
                'is_locked' => 0,
                'wrong_password_attempts' => 0,
                'locked_at' => null,
            ]);

            return redirect()->back()->with('success', "User {$user->email} has been unlocked.");
        }

        return redirect()->back()->with('error', 'User not found.');
    }
}
