<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_requests_driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('driver_id');
            $table->enum('status', ['pending', 'accepted', 'in-progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('request_id')->references('id')->on('delivery_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index('status', 'delivery_requests_assignments_status_idx1');
            $table->index('created_at', 'delivery_requests_assignments_created_at_idx1');
            $table->index('updated_at', 'delivery_requests_assignments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_requests_driver_assignments');
    }
};
