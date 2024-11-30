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
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_status_id')->constrained('order_statuses')->cascadeOnDelete()->cascadeOnUpdate();
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
