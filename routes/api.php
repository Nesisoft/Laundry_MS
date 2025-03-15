<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        require base_path('routes/api/v1/auth.php');
    });

    Route::prefix('user')->group(function () {
        require base_path('routes/api/v1/user.php');
    });
});
