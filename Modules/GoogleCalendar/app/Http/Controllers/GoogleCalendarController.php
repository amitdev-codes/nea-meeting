<?php

namespace Modules\GoogleCalendar\Http\Controllers;

use App\Models\User;
use App\Models\OAuthState;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class GoogleCalendarController extends Controller
{
    public function redirectToGoogle()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }
        $stateKey = Str::random(40);
        OAuthState::create([
            'state_key' => $stateKey,
            'user_id' => auth()->id(),
            'provider' => 'google',
            'expires_at' => now()->addMinutes(10)
        ]);
        
        return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/calendar'])->with(['state' => $stateKey,'access_type' => 'offline','prompt' => 'consent'])->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Check for OAuth errors first
            if ($request->has('error')) {
                return redirect()->route('login')->with('error', 'OAuth error: ' . $request->get('error_description', 'Unknown error'));
            }
            
            // Check for authorization code
            if (!$request->has('code')) {
                return redirect()->route('login')->with('error', 'No authorization code received from Google');
            }
            
            // Get state from request
            $stateKey = $request->get('state');
            if (!$stateKey) {
                return redirect()->route('login')->with('error', 'Invalid OAuth state');
            }
            
            // Find the OAuth state record
            $oauthState = OAuthState::where('state_key', $stateKey)
                ->where('expires_at', '>', now())
                ->first();
            
            if (!$oauthState) {
                return redirect()->route('login')->with('error', 'OAuth state expired. Please try again.');
            }
        
            // Get the user
            $user = User::find($oauthState->user_id);
            if (!$user) {
                return redirect()->route('login')->with('error', 'User not found');
            }
            
            // Get Google user data using stateless mode
            $googleUser = Socialite::driver('google')->stateless()->user();
            // Calculate expiration time safely
            $expiresAt = now()->addHour(); // Default to 1 hour
            if (!empty($googleUser->expiresIn)) {
                $expiresAt = now()->addSeconds($googleUser->expiresIn);
            }
            
            // Update user with Google tokens
            $user->update([
                'google_access_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'google_token_expires_at' => $expiresAt,
                'google_calendar_id' => $googleUser->id
            ]);
            
            // Clean up - delete the OAuth state record
            $oauthState->delete();
            // Clean up expired states while we're at it
            OAuthState::where('expires_at', '<', now())->delete();
            // Log the user back in
            auth()->login($user);
            return redirect()->route('dashboard')->with('success', 'Google Calendar connected successfully!');
            
        } catch (\Exception $e) {
            Log::error('Google Calendar OAuth Error:', [
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->route('login')->with('error', 'Failed to connect Google Calendar. Please try again.');
        }
    }
    public function disconnect()
    {
        User::where('id', auth()->id())->update([
            'google_access_token' => null,
            'google_refresh_token' => null,
            'google_token_expires_at' => null
        ]);
        return redirect('/dashboard')->with('success', 'Google Calendar disconnected');
    }
}
