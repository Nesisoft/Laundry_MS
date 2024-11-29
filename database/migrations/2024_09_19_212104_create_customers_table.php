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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->string('phone_number', 20);
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->string('phone_number', 20);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
