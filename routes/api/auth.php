<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

Route::post('/verify-product-key', [AuthApiController::class, 'verifyProductKey']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/change-password', [AuthApiController::class, 'changePassword']);
});
