<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('/{userType}')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password-reset-request', [AuthController::class, 'sendResetLinkEmail']);
    Route::post('/password-reset', [AuthController::class, 'resetPassword']);
    Route::post('/password-change', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

    // Password reset form display
    Route::get('/password-reset/{token}', function ($userType, $token) {
        return view('passwords.reset', ['token' => $token, 'userType' => $userType]);
    })->name('password.reset');

    // Handle password reset form submission
    Route::post('/password-reset', [AuthController::class, 'resetPassword'])->name('password.update');
});
