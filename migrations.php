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

    // Polymorphic relationship columns
    $table->unsignedBigInteger('addressable_id')->nullable();
    $table->string('addressable_type')->nullable();
    $table->timestamps();

    // Add indexes
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

    // Define indexes
    $table->index('key', 'business_key_idx1');
    $table->index('value', 'business_value_idx1');
    $table->index('created_at', 'business_created_at_idx1');
    $table->index('updated_at', 'business_updated_at_idx1');
});

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->enum('role', ['admin', 'manager', 'employee']);
    $table->string('username')->unique();
    $table->string('password');
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamps();

    // Indexes
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
    $table->timestamps();

    // Indexes
    $table->index('email', 'employees_email_idx1');
    $table->index('phone_number', 'employees_phone_number_idx1');
    $table->index('first_name', 'employees_first_name_idx1');
    $table->index('last_name', 'employees_last_name_idx1');
    $table->index('full_name', 'employees_full_name_idx1');
    $table->index('sex', 'employees_sex_idx1');
    $table->index('created_at', 'employees_created_at_idx1');
    $table->index('updated_at', 'employees_updated_at_idx1');
});


