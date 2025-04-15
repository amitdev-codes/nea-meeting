<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CumulativeProgressController;

Route::middleware(['locale'])->group(function () {
    Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switcher');
    
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

    Route::post('/convert-image-to-base64', function (Request $request) {
        $imageUrl = $request->input('image_url');

        // Ensure full URL
        if (! str_starts_with($imageUrl, 'http')) {
            $imageUrl = url($imageUrl);
        }

        // Download and convert image
        try {
            $imageData = file_get_contents($imageUrl);
            $base64 = base64_encode($imageData);
            $mime = mime_content_type($imageUrl) ?: 'image/jpeg';

            return response()->json([
                'base64' => "data:$mime;base64,$base64",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'base64' => null,
                'error' => $e->getMessage(),
            ], 400);
        }
    });

    require __DIR__.'/auth.php';
});

// admin basic routes
require __DIR__.'/admin.php';