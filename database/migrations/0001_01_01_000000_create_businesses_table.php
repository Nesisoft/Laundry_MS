<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null')->onUpdate('cascade');
            $table->string('access_token', 255)->unique('businesses_access_token');
            $table->string('name', 255);
            $table->enum('mode', ['live', 'local'])->default('live');
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('motto', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('mode', 'businesses_mode_idx1');
            $table->index('name', 'businesses_name_idx1');
            $table->index('phone', 'businesses_phone_idx1');
            $table->index('email', 'businesses_email_idx1');
            $table->index('logo', 'businesses_logo_idx1');
            $table->index('banner', 'businesses_banner_idx1');
            $table->index('motto', 'businesses_motto_idx1');
            $table->index('created_at', 'businesses_created_at_idx1');
            $table->index('updated_at', 'businesses_updated_at_idx1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
