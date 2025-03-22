<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function () {
    Route::post('/', [EmployeeController::class, 'login']);
    Route::put('/', [EmployeeController::class, 'sendResetLinkEmail']);
    Route::get('/', [EmployeeController::class, 'resetPassword']);
    Route::delete('/', [EmployeeController::class, 'resetPassword']);
});
