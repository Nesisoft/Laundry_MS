<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_request_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();

            // Indexes
            $table->index('amount', 'pickup_request_payments_amount_idx1');
            $table->index('method', 'pickup_request_payments_method_idx1');
            $table->index('status', 'pickup_request_payments_status_idx1');
            $table->index('created_at', 'pickup_request_payments_created_at_idx1');
            $table->index('updated_at', 'pickup_request_payments_updated_at_idx1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_request_payments');
    }
};
