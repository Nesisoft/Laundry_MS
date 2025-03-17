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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 255);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('name', 'branches_name_idx1');
            $table->index('phone', 'branches_phone_idx1');
            $table->index('email', 'branches_email_idx1');
            $table->index('created_at', 'branches_created_at_idx1');
            $table->index('updated_at', 'branches_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
