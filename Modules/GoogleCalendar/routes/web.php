<?php

use Illuminate\Support\Facades\Route;
use Modules\GoogleCalendar\Http\Controllers\GoogleCalendarController;
use Modules\GoogleCalendar\Http\Controllers\GoogleCalendarSettingController;


Route::group(['prefix' => 'googlecalendar', 'middleware' => ['locale']], function () {
    Route::resource('google-calendar-settings', GoogleCalendarSettingController::class)->names('admin.google-calendar-settings');
});

// In web.php
Route::get('/test-auth', function () {
    return [
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user(),
        'auth_id' => auth()->id(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'guard' => config('auth.defaults.guard'),
        'user_provider' => config('auth.guards.web.provider'),
    ];
})->name('test.auth');

// In web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/auth/google', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.auth');

});
Route::get('/auth/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('/auth/google/disconnect', [GoogleCalendarController::class, 'disconnect'])->name('google.disconnect');