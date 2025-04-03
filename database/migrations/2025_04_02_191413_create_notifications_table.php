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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('notification_template_id')->constrained('notification_templates')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['sms', 'email'])->default('sms');
            $table->enum('to', ['all', 'specific'])->default('all');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null')->onUpdate('cascade');
            $table->text('message');
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Define indexes
            $table->index('type', 'notifications_type_idx1');
            $table->index('to', 'notifications_to_idx1');
            $table->index('archived', 'notifications_archived_idx1');
            $table->index('created_at', 'notifications_created_at_idx1');
            $table->index('updated_at', 'notifications_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
