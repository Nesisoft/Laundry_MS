<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();  // New image column
            $table->timestamps();

            // Indexes
            $table->index('name', 'app_items_name_idx1');
            $table->index('image', 'app_items_image_idx1');
            $table->index('created_at', 'app_items_created_at_idx1');
            $table->index('updated_at', 'app_items_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_items');
    }
};
