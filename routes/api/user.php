<?php

use App\Http\Controllers\DeliveryRequestController;
use App\Http\Controllers\DeliveryRequestPaymentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalConfigController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\PickupRequestPaymentController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('config')->group(function () {
        Route::get('/', [LocalConfigController::class, 'fetchAll']); // Fetch all config values
        Route::get('/{key}', [LocalConfigController::class, 'fetchOne']); // Fetch a single config by key
        Route::post('/', [LocalConfigController::class, 'add']); // Add a new config entry
        Route::put('/reset', [LocalConfigController::class, 'resetDefaultConfigs']);
        Route::put('/logo', [LocalConfigController::class, 'setLogo']);
        Route::put('/update', [LocalConfigController::class, 'update']);
        Route::put('/', [LocalConfigController::class, 'setAllConfigValues']); // Keep this last
        Route::delete('/{key}', [LocalConfigController::class, 'delete']);
        Route::get('/value/{key}', [LocalConfigController::class, 'getValue']); // Get the value of a specific config key
    });

    Route::prefix('employee')->group(function () {
        Route::get('/archived', [EmployeeController::class, 'fetchArchived']);
        Route::get('/{employee_id}', [EmployeeController::class, 'fetchOne']); // Fetch a single employee by key
        Route::get('/', [EmployeeController::class, 'fetchAll']); // Fetch all employee values
        Route::post('/address/{employee_id}', [EmployeeController::class, 'addAddress']);
        Route::post('/', [EmployeeController::class, 'add']); // Add a new employee entry
        Route::put('/archive/{employee_id}', [EmployeeController::class, 'archive']);
        Route::put('/address/{employee_id}', [EmployeeController::class, 'updateAddress']);
        Route::put('/', [EmployeeController::class, 'update']);
        Route::delete('/{employee_id}', [EmployeeController::class, 'delete']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);               // Get list of users (with filters)
        Route::get('/{id}', [UserController::class, 'show']);            // Get a single user by ID
        Route::post('/', [UserController::class, 'store']);              // Create new user
        Route::put('/{id}', [UserController::class, 'update']);          // Update user by ID
        Route::put('/archive/{id}', [UserController::class, 'archive']); // Archive a user
        Route::put('/restore/{id}', [UserController::class, 'restore']); // Restore a user
        Route::delete('/{id}', [UserController::class, 'destroy']);      // Permanently delete a user
    });

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);         // Get all items
        Route::get('/archived', [ItemController::class, 'fetchArchived']); // Archived items
        Route::get('/{id}', [ItemController::class, 'show']);       // Get one
        Route::post('/', [ItemController::class, 'store']);         // Create
        Route::put('/{id}', [ItemController::class, 'update']);     // Update
        Route::put('/archive/{id}', [ItemController::class, 'archive']); // Archive
        Route::put('/restore/{id}', [ItemController::class, 'restore']); // Restore archived
        Route::delete('/{id}', [ItemController::class, 'destroy']); // Delete
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index']);
        Route::get('/{id}', [DiscountController::class, 'show']);
        Route::post('/', [DiscountController::class, 'store']);
        Route::put('/{id}', [DiscountController::class, 'update']);
        Route::delete('/{id}', [DiscountController::class, 'destroy']);
        Route::put('/archive/{id}', [DiscountController::class, 'archive']);
        Route::put('/restore/{id}', [DiscountController::class, 'restore']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);         // List all
        Route::post('/', [OrderController::class, 'store']);        // Create
        Route::get('/{id}', [OrderController::class, 'show']);      // Show one
        Route::put('/{id}', [OrderController::class, 'update']);    // Update
        Route::put('/{id}/archive', [OrderController::class, 'archive']); // Archive
        Route::put('/{id}/restore', [OrderController::class, 'restore']); // Restore
        Route::delete('/{id}', [OrderController::class, 'destroy']); // Delete
    });

    Route::prefix('order-items')->group(function () {
        Route::get('/', [OrderItemController::class, 'index']);
        Route::post('/', [OrderItemController::class, 'store']);
        Route::delete('/{id}', [OrderItemController::class, 'destroy']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::get('/{id}', [InvoiceController::class, 'show']);
        Route::post('/{invoice}/send-sms', [InvoiceController::class, 'sendInvoiceSMS']);
    });

    Route::prefix('invoice-payments')->group(function () {
        Route::get('/payments', [InvoicePaymentController::class, 'index']);
        Route::post('/payments', [InvoicePaymentController::class, 'store']);
    });

    // Pickup Requests
    Route::prefix('pickup-requests')->group(function () {
        Route::controller(PickupRequestController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/payments', 'getAllPayments');

            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

            Route::patch('/{id}/status', 'updateStatus');
            Route::patch('/{id}/archive', 'archive');
            Route::patch('/{id}/restore', 'restore');
            Route::post('/{id}/drivers', 'assignDriver');
            Route::patch('/{id}/drivers/{assignmentId}', 'updateDriverAssignmentStatus');
            Route::post('/{id}/payments', 'recordPayment');
            Route::get('/{id}/payments', 'getPayments');
        });
    });

    // Delivery Requests
    Route::prefix('delivery-requests')->group(function () {
        Route::controller(DeliveryRequestController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/payments', 'getAllPayments');

            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

            Route::patch('/{id}/status', 'updateStatus');
            Route::patch('/{id}/archive', 'archive');
            Route::patch('/{id}/restore', 'restore');
            Route::post('/{id}/drivers', 'assignDriver');
            Route::patch('/{id}/drivers/{assignmentId}', 'updateDriverAssignmentStatus');
            Route::post('/{id}/payments', 'recordPayment');
            Route::get('/{id}/payments', 'getPayments');
        });
    });
});
