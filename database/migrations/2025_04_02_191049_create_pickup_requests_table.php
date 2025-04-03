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
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('location')->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->date('date');
            $table->time('time');
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->boolean('archived')->default(false);
            $table->timestamps();


            // Indexes
            $table->index('location', 'pickup_requests_location_idx1');
            $table->index('latitude', 'pickup_requests_latitude_idx1');
            $table->index('longitude', 'pickup_requests_longitude_idx1');
            $table->index('date', 'pickup_requests_date_idx1');
            $table->index('time', 'pickup_requests_time_idx1');
            $table->index('amount', 'pickup_requests_amount_idx1');
            $table->index('archived', 'pickup_requests_archived_idx1');
            $table->index('created_at', 'pickup_requests_created_at_idx1');
            $table->index('updated_at', 'pickup_requests_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
