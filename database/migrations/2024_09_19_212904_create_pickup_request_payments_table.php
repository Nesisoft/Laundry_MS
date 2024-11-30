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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('request_id')->constrained('pickup_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();

            // Indexes
            $table->index('amount');
            $table->index('method');
            $table->index('status');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_request_payments');
    }
};
