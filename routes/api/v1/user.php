<?php

use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\ManagerController;
use App\Http\Controllers\V1\StaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager-dashboard', [ManagerController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/manager-dashboard', [StaffController::class, 'dashboard']);
});
