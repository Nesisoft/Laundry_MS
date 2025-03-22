<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('rating');
            $table->index('created_at', 'service_ratings_created_at_idx1');
            $table->index('updated_at', 'service_ratings_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_ratings');
    }
};
