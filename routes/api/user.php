<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDiscountController;
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
use App\Models\Customer;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('config')->group(function () {
        Route::get('/', [LocalConfigController::class, 'fetchAll']); // Fetch all config values
        Route::post('/', [LocalConfigController::class, 'add']); // Add a new config entry
        Route::put('/', [LocalConfigController::class, 'setAllConfigValues']); // Keep this last
        Route::put('/reset', [LocalConfigController::class, 'resetDefaultConfigs']);
        Route::put('/logo', [LocalConfigController::class, 'setLogo']);
        Route::put('/update', [LocalConfigController::class, 'update']);
        Route::get('/{key}', [LocalConfigController::class, 'fetchOne']); // Fetch a single config by key
        Route::delete('/{key}', [LocalConfigController::class, 'delete']);
        Route::get('/value/{key}', [LocalConfigController::class, 'getValue']); // Get the value of a specific config key
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);               // Get list of users (with filters)
        Route::post('/', [UserController::class, 'store']);              // Create new user
        Route::get('/{id}', [UserController::class, 'show']);            // Get a single user by ID
        Route::put('/{id}', [UserController::class, 'update']);          // Update user by ID
        Route::delete('/{id}', [UserController::class, 'destroy']);      // Permanently delete a user
        Route::put('/archive/{id}', [UserController::class, 'archive']); // Archive a user
        Route::put('/restore/{id}', [UserController::class, 'restore']); // Restore a user
    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'fetchAll']); // Fetch all employee values
        Route::post('/', [EmployeeController::class, 'add']); // Add a new employee entry
        Route::put('/', [EmployeeController::class, 'update']);
        Route::get('/archived', [EmployeeController::class, 'fetchArchived']);
        Route::get('/{employee_id}', [EmployeeController::class, 'fetchOne']); // Fetch a single employee by key
        Route::delete('/{employee_id}', [EmployeeController::class, 'delete']);
        Route::post('/address/{employee_id}', [EmployeeController::class, 'addAddress']);
        Route::put('/archive/{employee_id}', [EmployeeController::class, 'archive']);
        Route::put('/address/{employee_id}', [EmployeeController::class, 'updateAddress']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']); // Fetch all employee values
        Route::post('/', [CustomerController::class, 'store']); // Add a new employee entry
        Route::put('/', [CustomerController::class, 'update']);
        Route::get('/{employee_id}', [CustomerController::class, 'show']); // Fetch a single employee by key
        Route::delete('/{employee_id}', [CustomerController::class, 'destroy']); // Fetch a single employee by key
        Route::patch('/{employee_id}', [CustomerController::class, 'restore']);
        Route::post('/address/{employee_id}', [CustomerController::class, 'addAddress']);
        Route::patch('/archive/{employee_id}', [CustomerController::class, 'archive']);
        Route::patch('/address/{employee_id}', [CustomerController::class, 'updateAddress']);
    });

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);         // Get all items
        Route::post('/', [ItemController::class, 'store']);         // Create
        Route::delete('/{id}', [ItemController::class, 'destroy']); // Delete
        Route::get('/{id}', [ItemController::class, 'show']);       // Get one
        Route::put('/{id}', [ItemController::class, 'update']);     // Update
        Route::get('/archived', [ItemController::class, 'fetchArchived']); // Archived items
        Route::put('/archive/{id}', [ItemController::class, 'archive']); // Archive
        Route::put('/restore/{id}', [ItemController::class, 'restore']); // Restore archived
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index']);
        Route::post('/', [DiscountController::class, 'store']);
        Route::get('/{id}', [DiscountController::class, 'show']);
        Route::put('/{id}', [DiscountController::class, 'update']);
        Route::delete('/{id}', [DiscountController::class, 'destroy']);
        Route::put('/archive/{id}', [DiscountController::class, 'archive']);
        Route::put('/restore/{id}', [DiscountController::class, 'restore']);

        Route::get('/customer', [CustomerDiscountController::class, 'index']);
        Route::post('/customer', [CustomerDiscountController::class, 'store']);
        Route::get('/customer/{id}', [CustomerDiscountController::class, 'show']);
        Route::put('/customer/{id}', [CustomerDiscountController::class, 'update']);
        Route::delete('/customer/{id}', [CustomerDiscountController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);         // List all
        Route::post('/', [OrderController::class, 'store']);        // Create
        Route::get('/{id}', [OrderController::class, 'show']);      // Show one
        Route::put('/{id}', [OrderController::class, 'update']);    // Update
        Route::put('/{id}/archive', [OrderController::class, 'archive']); // Archive
        Route::put('/{id}/restore', [OrderController::class, 'restore']); // Restore
        Route::delete('/{id}', [OrderController::class, 'destroy']); // Delete

        Route::get('/items', [OrderItemController::class, 'index']);
        Route::post('/items', [OrderItemController::class, 'store']);
        Route::delete('/items/{id}', [OrderItemController::class, 'destroy']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/', [InvoiceController::class, 'store']);
        Route::get('/{id}', [InvoiceController::class, 'show']);
        Route::post('/{invoice}/send-sms', [InvoiceController::class, 'sendInvoiceSMS']);

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
