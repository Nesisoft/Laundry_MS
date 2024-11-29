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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
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
            $table->index('email', 'drivers_email_idx1');
            $table->index('phone_number', 'drivers_phone_number_idx1');
            $table->index('first_name', 'drivers_first_name_idx1');
            $table->index('last_name', 'drivers_last_name_idx1');
            $table->index('full_name', 'drivers_full_name_idx1');
            $table->index('sex', 'drivers_sex_idx1');
            $table->index('created_at', 'drivers_created_at_idx1');
            $table->index('updated_at', 'drivers_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
