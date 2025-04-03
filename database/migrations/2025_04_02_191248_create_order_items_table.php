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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2)->storedAs('amount * quantity');
            $table->timestamps();

            // Indexes
            $table->index('amount', 'order_items_amount_idx1');
            $table->index('quantity', 'order_items_quantity_idx1');
            $table->index('total_amount', 'order_items_total_amount_idx1');
            $table->index('created_at', 'order_items_created_at_idx1');
            $table->index('updated_at', 'order_items_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
