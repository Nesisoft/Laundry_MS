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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['ready for washing', 'ready for ironing', 'ready for pickup', 'ready for delivery']);
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('status', 'orders_status_idx1');
            $table->index('archived', 'orders_archived_idx1');
            $table->index('created_at', 'orders_created_at_idx1');
            $table->index('updated_at', 'orders_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
