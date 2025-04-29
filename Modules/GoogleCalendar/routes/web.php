<?php

use Illuminate\Support\Facades\Route;
use Modules\GoogleCalendar\Http\Controllers\GoogleCalendarSettingController;


Route::group(['prefix' => 'googlecalendar', 'middleware' => ['locale']], function () {
    Route::resource('google-calendar-settings', GoogleCalendarSettingController::class)->names('admin.google-calendar-settings');
});
