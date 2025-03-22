<?php



Schema::create('addresses', function (Blueprint $table) {
    $table->id();
    $table->string('street')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('zip_code')->nullable();
    $table->string('country')->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();

    $table->unsignedBigInteger('addressable_id')->nullable();
    $table->string('addressable_type')->nullable();
    $table->timestamps();

    $table->index('street', 'addresses_street_idx1');
    $table->index('city', 'addresses_city_idx1');
    $table->index('state', 'addresses_state_idx1');
    $table->index('zip_code', 'addresses_zip_code_idx1');
    $table->index('country', 'addresses_country_idx1');
    $table->index('latitude', 'addresses_latitude_idx1');
    $table->index('longitude', 'addresses_longitude_idx1');
    $table->index('created_at', 'addresses_created_at_idx1');
    $table->index('updated_at', 'addresses_updated_at_idx1');
});

Schema::create('business', function (Blueprint $table) {
    $table->id();
    $table->string('key', 255)->unique();
    $table->string('value', 255)->nullable();
    $table->timestamps();

    $table->index('key', 'business_key_idx1');
    $table->index('value', 'business_value_idx1');
    $table->index('created_at', 'business_created_at_idx1');
    $table->index('updated_at', 'business_updated_at_idx1');
});

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
    $table->enum('role', ['admin', 'manager', 'employee']);
    $table->string('username')->unique();
    $table->string('password');
    $table->timestamp('email_verified_at')->nullable();
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('archived', 'users_archived_idx1');
    $table->index('role', 'users_role_idx1');
    $table->index('username', 'users_username_idx1');
    $table->index('password', 'users_password_idx1');
    $table->index('created_at', 'users_created_at_idx1');
    $table->index('updated_at', 'users_updated_at_idx1');
});

Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('address_id')->nullable();
    $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
    $table->string('role');
    $table->string('phone_number', 20);
    $table->string('first_name', 255);
    $table->string('last_name', 255);
    $table->decimal('salary')->nullable();
    $table->enum('sex', ['male', 'female'])->default('male');
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('archived', 'employees_archived_idx1');
    $table->index('email', 'employees_email_idx1');
    $table->index('phone_number', 'employees_phone_number_idx1');
    $table->index('first_name', 'employees_first_name_idx1');
    $table->index('last_name', 'employees_last_name_idx1');
    $table->index('full_name', 'employees_full_name_idx1');
    $table->index('sex', 'employees_sex_idx1');
    $table->index('created_at', 'employees_created_at_idx1');
    $table->index('updated_at', 'employees_updated_at_idx1');
});

Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->string('name');
    $table->text('description')->nullable();
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('name', 'services_name_idx1');
    $table->index('archived', 'services_archived_idx1');
    $table->index('created_at', 'services_created_at_idx1');
    $table->index('updated_at', 'services_updated_at_idx1');
});

Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->string('number', 255)->unique();
    $table->enum('type', ['car', 'motorcycle', 'bicycle'])->default('car');
    $table->string('model', 255)->nullable();
    $table->string('year', 255)->nullable();
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('number', 'vehicles_number_idx1');
    $table->index('type', 'vehicles_type_idx1');
    $table->index('model', 'vehicles_model_idx1');
    $table->index('year', 'vehicles_year_idx1');
    $table->index('archived', 'vehicles_archived_idx1');
    $table->index('created_at', 'vehicles_created_at_idx1');
    $table->index('updated_at', 'vehicles_updated_at_idx1');
});

Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->unsignedBigInteger('address_id')->nullable();
    $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
    $table->string('first_name', 255)->nullable();
    $table->string('last_name', 255)->nullable();
    $table->string('phone_number', 20)->unique()->nullable();
    $table->string('email')->unique()->nullable();
    $table->enum('sex', ['male', 'female'])->default('male');
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('first_name', 'customers_first_name_idx1');
    $table->index('last_name', 'customers_last_name_idx1');
    $table->index('phone_number', 'customers_phone_number_idx1');
    $table->index('email', 'customers_email_idx1');
    $table->index('sex', 'customers_sex_idx1');
    $table->index('archived', 'customers_archived_idx1');
    $table->index('created_at', 'customers_created_at_idx1');
    $table->index('updated_at', 'customers_updated_at_idx1');
});

Schema::create('discounts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->enum('type', ['percentage', 'amount'])->default('percentage');
    $table->decimal('value', 10, 2);
    $table->string('description')->nullable();
    $table->date('expiration_date')->nullable();
    $table->boolean('archived')->default(false);
    $table->timestamps();

    $table->index('code', 'discounts_code_idx1');
    $table->index('type', 'discounts_type_idx1');
    $table->index('value', 'discounts_value_idx1');
    $table->index('expiration_date', 'discounts_expiration_date_idx1');
    $table->index('archived', 'discounts_archived_idx1');
    $table->index('created_at', 'discounts_created_at_idx1');
    $table->index('updated_at', 'discounts_updated_at_idx1');
});

Schema::create('customer_discounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete()->cascadeOnUpdate();
    $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->timestamps();

    $table->index('created_at');
    $table->index('updated_at');
});

Schema::create('pickup_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
    $table->foreignId('service_id')->constrained('services')->cascadeOnDelete()->cascadeOnUpdate();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->string('location')->nullable();
    $table->decimal('latitude', 9, 6)->nullable();
    $table->decimal('longitude', 9, 6)->nullable();
    $table->date('date');
    $table->time('time');
    $table->decimal('amount', 10, 2);
    $table->text('note')->nullable();
    $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
    $table->boolean('archived')->default(false);
    $table->timestamps();


    // Indexes
    $table->index('location', 'pickup_requests_location_idx1');
    $table->index('latitude', 'pickup_requests_latitude_idx1');
    $table->index('longitude', 'pickup_requests_longitude_idx1');
    $table->index('date', 'pickup_requests_date_idx1');
    $table->index('time', 'pickup_requests_time_idx1');
    $table->index('amount', 'pickup_requests_amount_idx1');
    $table->index('archived', 'pickup_requests_archived_idx1');
    $table->index('created_at', 'pickup_requests_created_at_idx1');
    $table->index('updated_at', 'pickup_requests_updated_at_idx1');
});

Schema::create('pickup_request_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
    $table->decimal('amount', 10, 2);
    $table->enum('method', ['Cash', 'MoMo', 'Card']);
    $table->enum('status', ['paid', 'unpaid']);
    $table->timestamps();

    // Indexes
    $table->index('amount', 'pickup_request_payments_amount_idx1');
    $table->index('method', 'pickup_request_payments_method_idx1');
    $table->index('status', 'pickup_request_payments_status_idx1');
    $table->index('created_at', 'pickup_request_payments_created_at_idx1');
    $table->index('updated_at', 'pickup_request_payments_updated_at_idx1');
});

Schema::create('pickup_request_driver_assignments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete()->cascadeOnUpdate();
    $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
    $table->enum('status', ['in-progress', 'completed', 'cancelled'])->default('in-progress');
    $table->timestamps();

    // Indexes
    $table->index('status', 'pickup_request_driver_assignments_status_idx1');
    $table->index('created_at', 'pickup_request_driver_assignments_created_at_idx1');
    $table->index('updated_at', 'pickup_request_driver_assignments_updated_at_idx1');
});

Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->string('name');
    $table->decimal('amount', 10, 2);
    $table->string('image')->nullable();  // New image column
    $table->boolean('archived')->default(false);
    $table->timestamps();

    // Indexes
    $table->index('name', 'items_name_idx1');
    $table->index('image', 'items_image_idx1');
    $table->index('archived', 'items_archived_idx1');
    $table->index('created_at', 'items_created_at_idx1');
    $table->index('updated_at', 'items_updated_at_idx1');
});

Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
    $table->string('status');
    $table->boolean('archived')->default(false);
    $table->timestamps();

    // Indexes
    $table->index('status', 'orders_status_idx1');
    $table->index('archived', 'orders_archived_idx1');
    $table->index('created_at', 'orders_created_at_idx1');
    $table->index('updated_at', 'orders_updated_at_idx1');
});

Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
    $table->foreignId('item_id')->constrained('items')->cascadeOnDelete()->cascadeOnUpdate();
    $table->decimal('amount', 10, 2);
    $table->integer('quantity');
    $table->decimal('total_amount', 10, 2)->storedAs('amount * quantity');
    $table->timestamps();

    // Indexes
    $table->index('amount', 'order_items_amount_idx1');
    $table->index('quantity', 'order_items_quantity_idx1');
    $table->index('total_amount', 'order_items_total_amount_idx1');
    $table->index('created_at', 'order_items_created_at_idx1');
    $table->index('updated_at', 'order_items_updated_at_idx1');
});

Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
    $table->decimal('amount', 10, 2);
    $table->decimal('discount', 10, 2);
    $table->decimal('discount_amount', 10, 2)->storedAs('`amount` * `discount`');
    $table->decimal('actual_amount', 10, 2)->storedAs('`amount` - `discount_amount`');
    $table->enum('status', ['fully paid', 'partly paid', 'unpaid'])->default('unpaid');
    $table->boolean('smsed')->default(false);
    $table->boolean('archived')->default(false);
    $table->timestamps();

    // Indexes
    $table->index('amount', 'invoices_amount_idx1');
    $table->index('discount_amount', 'invoices_discount_amount_idx1');
    $table->index('discount', 'invoices_discount_idx1');
    $table->index('actual_amount', 'invoices_actual_amount_idx1');
    $table->index('status', 'invoices_status_idx1');
    $table->index('archived', 'invoices_archived_idx1');
    $table->index('created_at', 'invoices_created_at_idx1');
    $table->index('updated_at', 'invoices_updated_at_idx1');
});

Schema::create('invoice_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete()->cascadeOnUpdate();
    $table->decimal('amount', 10, 2);
    $table->enum('method', ['Cash', 'MoMo', 'Card']);
    $table->enum('status', ['fully paid', 'partly paid']);
    $table->timestamps();

    // Indexes
    $table->index('amount', 'invoice_payments_amount_idx1');
    $table->index('method', 'invoice_payments_method_idx1');
    $table->index('status', 'invoice_payments_status_idx1');
    $table->index('created_at', 'invoice_payments_created_at_idx1');
    $table->index('updated_at', 'invoice_payments_updated_at_idx1');
});

Schema::create('service_ratings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('added_by')->nullable();
    $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
    $table->integer('rating');
    $table->text('comment')->nullable();
    $table->timestamps();

    // Indexes
    $table->index('rating');
    $table->index('created_at', 'service_ratings_created_at_idx1');
    $table->index('updated_at', 'service_ratings_updated_at_idx1');
});

