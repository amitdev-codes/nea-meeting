<?php

use Illuminate\Support\Facades\Route;
use Modules\NeaMeeting\Http\Controllers\MeetingController;


Route::group(['prefix' => 'meetings', 'middleware' => ['locale']], function () {
    Route::resource('meetings', MeetingController::class)->names('admin.meetings');
    Route::get('get-by-date/{year}/{month}/{day}', [MeetingController::class, 'getByDate']);
    Route::get('get-meetings-dates/{year}/{month}', [MeetingController::class, 'getMeetingDates']);
});
