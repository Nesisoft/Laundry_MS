<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/reset-password-request', [AdminController::class, 'sendResetLinkEmail']);
    Route::post('/reset-password', [AdminController::class, 'resetPassword']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/register', [AdminController::class, 'register']);
        Route::post('/logout', [AdminController::class, 'logout']);
        Route::post('/change-password', [AdminController::class, 'changePassword']);

        Route::get('/me', [AdminController::class, 'me']);
    });
});

