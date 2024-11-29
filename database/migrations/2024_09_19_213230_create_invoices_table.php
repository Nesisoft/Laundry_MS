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
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->storedAs('`amount` * `discount`');
            $table->decimal('actual_amount', 10, 2)->storedAs('`amount` - `discount_amount`');
            $table->enum('status', ['fully paid', 'partly paid', 'unpaid'])->default('unpaid');
            $table->boolean('smsed')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('amount', 'invoices_amount_idx1');
            $table->index('discount_amount', 'invoices_discount_amount_idx1');
            $table->index('discount', 'invoices_discount_idx1');
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
