<?php

use App\Mail\TestMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\Auth\PasswordController;
use Modules\Settings\Services\DynamicEmailService;
use App\Http\Controllers\CumulativeProgressController;

Route::middleware(['locale'])->group(function () {
    Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switcher');
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::middleware(['auth'])->group(function () {
        Route::middleware('verified')->group(function () {
            Route::get('/dashboard', [DashBoardController::class, 'dashboard'])->name('dashboard');
            Route::get('/proxy-image', [ImageController::class, 'proxy'])->name('proxy.image');
            Route::resource('cumulative-progress',CumulativeProgressController::class)->names('admin.cumulative-progress');

        });

        Route::as('account.')->group(function () {
            Route::get('/account/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/account/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/account/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            Route::get('/account/change-password', [PasswordController::class, 'edit'])->name('password.edit');
            Route::get('/account/change-language', [DashBoardController::class, 'locale'])->name('locale');
        });
    });

    Route::get('/test-email', function () {
        try {
            $emailService = new DynamicEmailService();

            $emailService->send('amitdev67@gmail.com', new TestMailable());
            return 'Email sent successfully!';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });


    require __DIR__.'/auth.php';
});

// admin basic routes
require __DIR__.'/admin.php';