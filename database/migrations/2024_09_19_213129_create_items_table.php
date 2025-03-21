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
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('image')->nullable();  // New image column
            $table->timestamps();

            // Indexes
            $table->index('name', 'items_name_idx1');
            $table->index('image', 'items_image_idx1');
            $table->index('created_at', 'items_created_at_idx1');
            $table->index('updated_at', 'items_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
