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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
