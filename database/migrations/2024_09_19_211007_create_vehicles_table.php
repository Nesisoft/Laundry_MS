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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();  // Foreign key column
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable();  // Foreign key column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('number', 255)->unique();
            $table->enum('type', ['car', 'motorcycle'])->default('car');
            $table->string('model', 255)->nullable();
            $table->string('year', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('number', 'vehicles_number_idx1');
            $table->index('type', 'vehicles_type_idx1');
            $table->index('model', 'vehicles_model_idx1');
            $table->index('year', 'vehicles_year_idx1');
            $table->index('created_at', 'vehicles_created_at_idx1');
            $table->index('updated_at', 'vehicles_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
