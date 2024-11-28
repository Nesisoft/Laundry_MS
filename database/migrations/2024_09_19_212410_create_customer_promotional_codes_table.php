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
        Schema::create('customer_promotional_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code_id');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            
            $table->foreign('code_id')->references('id')->on('promotional_codes')->onDelete('cascade')->onUpdate('cascade');

            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_promotional_codes');
    }
};
