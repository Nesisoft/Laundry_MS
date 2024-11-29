<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['pickup', 'delivery', 'payment', 'general']);
            $table->string('title', 255);
            $table->text('message');
            $table->timestamps();

            $table->index('type');
            $table->index('title');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
};
