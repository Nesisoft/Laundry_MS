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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('phone_number', 20);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('position', 255)->nullable();
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->timestamps();

            $table->index('phone_number', 'admins_phone_number_idx1');
            $table->index('first_name', 'admins_first_name_idx1');
            $table->index('last_name', 'admins_last_name_idx1');
            $table->index('full_name', 'admins_full_name_idx1');
            $table->index('sex', 'admins_sex_idx1');
            $table->index('created_at', 'admins_created_at_idx1');
            $table->index('updated_at', 'admins_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
