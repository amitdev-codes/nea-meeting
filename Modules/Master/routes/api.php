<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\DistrictController;
use Modules\Master\Http\Controllers\LocalLevelController;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\ProvinceController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('master', MasterController::class)->names('master');
    Route::apiResource('provinces', ProvinceController::class)->names('provinces');
    Route::apiResource('districts', DistrictController::class)->names('districts');
    Route::apiResource('localLevels', LocalLevelController::class)->names('localLevels');
    // Route::apiResource('componenets', LocalLevelController::class)->names('localLevels');
    // Route::apiResource('subComponenets', LocalLevelController::class)->names('localLevels');
});
