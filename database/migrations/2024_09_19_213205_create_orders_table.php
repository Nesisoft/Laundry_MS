<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code')->unique();
            $table->boolean('use_promo_code')->default(false);
            $table->foreignId('user_promotional_code_id')->nullable()->constrained('users_promotional_codes')->nullOnDelete()->cascadeOnUpdate();
            $table->string('status');
            $table->timestamps();

            // Indexes
            $table->index('status', 'orders_status_idx1');
            $table->index('created_at', 'orders_created_at_idx1');
            $table->index('updated_at', 'orders_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
