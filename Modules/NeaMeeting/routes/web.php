<?php

use Illuminate\Support\Facades\Route;
use Modules\NeaMeeting\Http\Controllers\MeetingController;
use Modules\NeaMeeting\Http\Controllers\MeetingRoomController;
use Modules\NeaMeeting\Http\Controllers\MeetingMinuteController;
use Modules\NeaMeeting\Http\Controllers\MeetingAttendeeController;


Route::group(['prefix' => 'meetings', 'middleware' => ['locale']], function () {
    Route::resource('meetings', MeetingController::class)->names('admin.meetings');
    Route::get('get-by-date/{year}/{month}/{day}', [MeetingController::class, 'getByDate']);
    Route::get('get-meetings-dates/{year}/{month}', [MeetingController::class, 'getMeetingDates']);

    Route::resource('meeting-rooms', MeetingRoomController::class)->names('admin.meeting-rooms');
    Route::resource('meeting-attendees', MeetingAttendeeController::class)->names('admin.meeting-attendees');
    Route::resource('meeting-minutes', MeetingMinuteController::class)->names('admin.meeting-minutes');
    Route::post('checkConflict', [MeetingController::class,'checkConflict'])->name('meetings.check-conflicts');
    Route::post('checkTimeValidation', [MeetingController::class,'checkTimeValidation'])->name('meetings.check-timeValidation');
    Route::post('/cancel/{id}', [MeetingController::class, 'cancel'])->name('meetings.cancel');

    Route::get('/{meeting}/notify', [MeetingController::class, 'sendNotifications'])->name('admin.meetings.notify');
});
