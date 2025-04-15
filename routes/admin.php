<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\Admin\Logs\LogController;
use App\Http\Controllers\NepaliCalendarController;
use App\Http\Controllers\Admin\BulkDeleteController;
use Modules\Master\Http\Controllers\DistrictController;
use Modules\Master\Http\Controllers\ProvinceController;
use App\Http\Controllers\Admin\Settings\ArtisanController;
use App\Http\Controllers\Admin\Settings\SettingController;
use App\Http\Controllers\Admin\UserManagement\RoleController;
use App\Http\Controllers\Admin\UserManagement\PermissionController;
use App\Http\Controllers\Admin\Settings\ApplicationSettingsController;

Route::middleware(['locale', 'auth'])->prefix('admin')->as('admin.')->group(function () {
    // Bulk delete
    Route::post('/bulk-delete/{model}', [BulkDeleteController::class, 'bulkDestroy'])->name('bulkDelete');
    Route::resource('logs', LogController::class);
    Route::resource('settings', SettingController::class);
    Route::resource('users', UserController::class);
    Route::post('users/import', [UserController::class,'import'])->name('users.import');
    Route::resource('roles', RoleController::class);
    Route::post('roles/import', [RoleController::class,'import'])->name('roles.import');
    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/import', [PermissionController::class,'import'])->name('permissions.import');
    Route::get('/site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
    Route::patch('/site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
    Route::post('/dropzone/upload', [DropzoneController::class, 'dropzoneUpload'])->name('dropzone.upload');
    Route::post('/dropzone/delete', [DropzoneController::class, 'dropzoneDelete'])->name('dropzone.delete');
    Route::resource('contacts', ContactController::class)->except('create', 'store');
    Route::post('/user/unlock', [ApplicationSettingsController::class, 'unlock'])->name('settings.unlock');
    Route::post('/artisan/optimize-clear', [ArtisanController::class, 'optimizeClear'])->name('artisan.optimize-clear');
    Route::post('/artisan/config-cache', [ArtisanController::class, 'configCache'])->name('artisan.config-cache');
    Route::post('/artisan/maintenance-toggle', [ArtisanController::class, 'maintenanceToggle'])->name('artisan.maintenance-toggle');
});
