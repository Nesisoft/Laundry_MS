<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::get('/manager-dashboard', [ManagerController::class, 'dashboard']);
});

Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
    Route::get('/task', [EmployeeController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::get('/task', [DriverController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::get('/task', [CustomerController::class, 'index']);
});
