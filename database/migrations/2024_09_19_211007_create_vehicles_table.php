<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('number', 255)->unique();
            $table->enum('type', ['car', 'motorcycle', 'bicycle'])->default('car');
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

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
