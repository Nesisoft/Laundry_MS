<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->time('time')->default(DB::raw('CURRENT_TIME'));
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('latitude', 'pickup_requests_latitude_idx1');
            $table->index('longitude', 'pickup_requests_longitude_idx1');
            $table->index('date', 'pickup_requests_date_idx1');
            $table->index('time', 'pickup_requests_time_idx1');
            $table->index('amount', 'pickup_requests_amount_idx1');
            $table->index('status', 'pickup_requests_status_idx1');
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
