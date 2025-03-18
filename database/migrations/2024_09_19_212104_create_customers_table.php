<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->timestamps();

            // Indexes
            $table->index('email', 'customers_email_idx1');
            $table->index('phone_number', 'customers_phone_number_idx1');
            $table->index('first_name', 'customers_first_name_idx1');
            $table->index('last_name', 'customers_last_name_idx1');
            $table->index('full_name', 'customers_full_name_idx1');
            $table->index('sex', 'customers_sex_idx1');
            $table->index('created_at', 'customers_created_at_idx1');
            $table->index('updated_at', 'customers_updated_at_idx1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
