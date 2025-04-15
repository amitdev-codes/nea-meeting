<?php

use Illuminate\Support\Facades\Route;
use Modules\NeaMeeting\Http\Controllers\MeetingController;


Route::group(['prefix' => 'meetings', 'middleware' => ['locale']], function () {
    Route::resource('meetings', MeetingController::class)->names('admin.meetings');
});
