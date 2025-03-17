<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('full_name', 255)->nullable();
            $table->enum('sex', ['male', 'female'])->default('female');
            $table->timestamps();

            // Indexes
            $table->index('email', 'managers_email_idx1');
            $table->index('phone_number', 'managers_phone_number_idx1');
            $table->index('first_name', 'managers_first_name_idx1');
            $table->index('last_name', 'managers_last_name_idx1');
            $table->index('full_name', 'managers_full_name_idx1');
            $table->index('sex', 'managers_sex_idx1');
            $table->index('created_at', 'managers_created_at_idx1');
            $table->index('updated_at', 'managers_updated_at_idx1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
