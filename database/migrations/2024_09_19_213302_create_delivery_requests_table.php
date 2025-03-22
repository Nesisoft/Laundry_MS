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
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('location', 'delivery_requests_location_idx1');
            $table->index('latitude', 'delivery_requests_latitude_idx1');
            $table->index('longitude', 'delivery_requests_longitude_idx1');
            $table->index('date', 'delivery_requests_date_idx1');
            $table->index('time', 'delivery_requests_time_idx1');
            $table->index('amount', 'delivery_requests_amount_idx1');
            $table->index('status', 'delivery_requests_status_idx1');
            $table->index('archived', 'delivery_requests_archived_idx1');
            $table->index('created_at', 'delivery_requests_created_at_idx1');
            $table->index('updated_at', 'delivery_requests_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_requests');
    }
};
