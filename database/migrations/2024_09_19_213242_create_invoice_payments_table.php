<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['Failed', 'Successful']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index('amount', 'invoice_payments_amount_idx1');
            $table->index('method', 'invoice_payments_method_idx1');
            $table->index('status', 'invoice_payments_status_idx1');
            $table->index('created_at', 'invoice_payments_created_at_idx1');
            $table->index('updated_at', 'invoice_payments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_payments');
    }
};
