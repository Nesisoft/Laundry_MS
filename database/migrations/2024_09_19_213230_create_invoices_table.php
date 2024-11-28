<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('actual_amount', 10, 2)->storedAs('`amount` - `discount_amount`');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index('amount', 'invoices_amount_idx1');
            $table->index('discount_amount', 'invoices_discount_amount_idx1');
            $table->index('actual_amount', 'invoices_actual_amount_idx1');
            $table->index('status', 'invoices_status_idx1');
            $table->index('created_at', 'invoices_created_at_idx1');
            $table->index('updated_at', 'invoices_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
