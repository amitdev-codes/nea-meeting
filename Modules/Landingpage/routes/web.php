<?php

use Illuminate\Support\Facades\Route;
use Modules\Landingpage\Http\Controllers\LandingpageController;
use Modules\Landingpage\Http\Controllers\SuccessStoriesController;
use Modules\Landingpage\Http\Controllers\LandingPageMenuController;

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

Route::get('',[LandingpageController::class,'index'])->name('admin.landingPage.index');
Route::get('/view/{id}', [LandingpageController::class, 'view'])->name('admin.landingPage.view');

