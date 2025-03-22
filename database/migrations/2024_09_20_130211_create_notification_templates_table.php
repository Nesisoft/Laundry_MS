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
            $table->enum('category', ['pickup', 'delivery', 'payment', 'promotion', 'rating', 'general']);
            $table->enum('medium', ['sms', 'email'])->default('sms');
            $table->string('title', 255);
            $table->text('message');
            $table->timestamps();

            $table->index('type', 'notification_templates_type_idx1');
            $table->index('title', 'notification_templates_title_idx1');
            $table->index('created_at', 'notification_templates_created_at_idx1');
            $table->index('updated_at', 'notification_templates_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
};
