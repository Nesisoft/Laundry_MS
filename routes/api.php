<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        require base_path('routes/api/v1/auth.php');
    });
    Route::prefix('admin')->group(function () {
        require base_path('routes/api/v1/admin.php');
    });
    Route::prefix('customer')->group(function () {
        require base_path('routes/api/v1/customer.php');
    });
    Route::prefix('driver')->group(function () {
        require base_path('routes/api/v1/driver.php');
    });
    Route::prefix('manager')->group(function () {
        require base_path('routes/api/v1/manager.php');
    });
});
