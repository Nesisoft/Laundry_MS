<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');  // Foreign key column
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('image')->nullable();  // New image column
            $table->timestamps();

            // Indexes
            $table->index('name', 'items_name_idx1');
            $table->index('created_at', 'items_created_at_idx1');
            $table->index('updated_at', 'items_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
