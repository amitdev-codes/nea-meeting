<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendar\Http\Controllers\CalendarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'calendar', 'middleware' => ['locale']], function () {
    // Route::resource('calendar', CalendarController::class)->names('calendar');
    Route::get('/nepali-calendar', [CalendarController::class, 'index'])->name('admin.calendar');
    Route::get('/get-months/{year}', [CalendarController::class, 'getMonths'])->name('calendar.year');
    Route::get('/get-years', [CalendarController::class, 'getYears'])->name('calendar.get-year');
    Route::get('/get-days/{year}/{month}', [CalendarController::class, 'getDays'])->name('calendar.month');
    Route::get('/get-calendar-data/{year}/{month}', [CalendarController::class, 'getCalendarData'])->name('calendar.data');
    Route::post('/get-calendar-grid-partial', [CalendarController::class, 'getCalendarGridPartial'])->name('calendar.grid');
});


