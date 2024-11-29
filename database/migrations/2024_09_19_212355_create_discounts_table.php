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
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('discount', 10, 2);
            $table->string('description')->nullable();
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
