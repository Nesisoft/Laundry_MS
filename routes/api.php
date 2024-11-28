<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(base_path('routes/api/v1/admin.php'));
    Route::prefix('user')->group(base_path('routes/api/v1/user.php'));
    Route::prefix('driver')->group(base_path('routes/api/v1/driver.php'));
    Route::prefix('manager')->group(base_path('routes/api/v1/manager.php'));
});
