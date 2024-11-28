<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('type', ['admin', 'customer', 'manager', 'driver'])->default('customer');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->string('phone_number', 20);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null')->onUpdate('cascade');

            // Indexes
            $table->index('email', 'users_email_idx1');
            $table->index('password', 'users_password_idx1');
            $table->index('type', 'users_type_idx1');
            $table->index('first_name', 'users_first_name_idx1');
            $table->index('last_name', 'users_last_name_idx1');
            $table->index('full_name', 'users_full_name_idx1');
            $table->index('phone_number', 'users_phone_number_idx1');
            $table->index('created_at', 'users_created_at_idx1');
            $table->index('updated_at', 'users_updated_at_idx1');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
