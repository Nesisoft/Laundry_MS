<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_request_driver_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('request_id')->constrained('delivery_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['in-progress', 'completed', 'cancelled'])->default('in-progress');
            $table->timestamps();

            // Indexes
            $table->index('status', 'delivery_request_driver_assignments_status_idx1');
            $table->index('created_at', 'delivery_request_driver_assignments_created_at_idx1');
            $table->index('updated_at', 'delivery_request_driver_assignments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_request_driver_driver_assignments');
    }
};
