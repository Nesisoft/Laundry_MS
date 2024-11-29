<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable();  // Foreign key column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('description')->nullable();
            $table->enum('apply_to', ['all', 'specific'])->default('all');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount', 10, 2);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
            
            
            $table->index('code', 'discounts_code_idx1');
            $table->index('discount_type', 'discounts_discount_type_idx1');
            $table->index('discount', 'discounts_discount_idx1');
            $table->index('expiration_date', 'discounts_expiration_date_idx1');
            $table->index('created_at', 'discounts_created_at_idx1');
            $table->index('updated_at', 'discounts_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};
