<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['in-app', 'email', 'sms']);
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->index('type', 'users_notification_settings_type_idx1');
            $table->index('enabled', 'users_notification_settings_enabled_idx1');
            $table->index('created_at', 'users_notification_settings_created_at_idx1');
            $table->index('updated_at', 'users_notification_settings_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_notification_settings');
    }
};
