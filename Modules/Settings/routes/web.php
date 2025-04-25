<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;
use Modules\Settings\Http\Controllers\SmsConfigurationController;
use Modules\Settings\Http\Controllers\EmailConfigurationController;

Route::group(['prefix' => 'settings', 'middleware' => ['locale']], function () {
     // Email Configurations
     Route::resource('email-configurations', EmailConfigurationController::class)->names('admin.email-configurations');
     Route::get('email-configurations/{emailConfiguration}/activate', [EmailConfigurationController::class, 'activate'])->name('email-configurations.activate');
     Route::get('testemail', [EmailConfigurationController::class, 'test'])->name('email-configurations.test');
     
     // SMS Configurations
     Route::resource('sms-configurations', SmsConfigurationController::class)->names('admin.sms-configurations');
     Route::get('sms-configurations/{smsConfiguration}/activate', [SmsConfigurationController::class, 'activate'])
         ->name('sms-configurations.activate');
     Route::get('testsms', [SmsConfigurationController::class, 'test'])->name('sms-configurations.test');
});
