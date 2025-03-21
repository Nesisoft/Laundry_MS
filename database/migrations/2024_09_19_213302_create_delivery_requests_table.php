<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('location', 255);
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->date('date');
            $table->time('time');
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index('status', 'delivery_requests_status_idx1');
            $table->index('created_at', 'delivery_requests_created_at_idx1');
            $table->index('updated_at', 'delivery_requests_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_requests');
    }
};
