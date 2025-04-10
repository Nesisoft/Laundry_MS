<?php

use App\Http\Controllers\LocalConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Setup wizard routes
// Route::get('/', function () {
//     if (config('app.setup_completed', false)) {
//         return redirect('/login');
//     }
//     return view('config/verify-key');
// });

// // Setup routes
// Route::prefix('setup')->group(function () {
//     Route::get('/verify-key', [LocalConfigController::class, 'showVerifyKey'])->name('setup.verify-key');
//     Route::post('/verify-key', [LocalConfigController::class, 'verifyKey'])->name('setup.verify-key.submit');

//     Route::get('/system-config', [LocalConfigController::class, 'showSystemConfig'])->name('setup.system-config');
//     Route::post('/system-config', [LocalConfigController::class, 'saveSystemConfig'])->name('setup.system-config.submit');

//     Route::get('/add-manager', [LocalConfigController::class, 'showAddManager'])->name('setup.add-manager');
//     Route::post('/add-manager', [LocalConfigController::class, 'saveManager'])->name('setup.add-manager.submit');

//     Route::get('/complete', [LocalConfigController::class, 'complete'])->name('setup.complete');
// });

// After setup is completed
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     // Add more routes for your application here
// });
