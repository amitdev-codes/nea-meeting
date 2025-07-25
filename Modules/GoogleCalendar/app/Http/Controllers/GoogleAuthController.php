<?php

namespace Modules\GoogleCalendar\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\GoogleCalendar\Models\UserGoogleToken;
use Modules\GoogleCalendar\Services\GoogleCalendarService;

class GoogleAuthController extends Controller
{
    protected $googleCalendar;

    public function __construct(GoogleCalendarService $googleCalendar)
    {
        $this->googleCalendar = $googleCalendar;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleCalendar->getAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect('/dashboard')->with('error', 'Google authentication failed');
        }

        try {
            $tokenData = $this->googleCalendar->handleCallback($request->code);
            // Save or update user's Google token
            UserGoogleToken::updateOrCreate(['user_id' => auth()->id()],$tokenData);
            return redirect('/dashboard')->with('success', 'Google Calendar connected successfully!');
        } catch (\Exception $e) {
            return redirect('/dashboard')->with('error', 'Failed to connect Google Calendar: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        UserGoogleToken::where('user_id', auth()->id())->delete();
        return redirect('/dashboard')->with('success', 'Google Calendar disconnected');
    }
}
