<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            // Indexes
            $table->index('name', 'order_statuses_name_idx1');
            $table->index('created_at', 'order_statuses_created_at_idx1');
            $table->index('updated_at', 'order_statuses_updated_at_idx1');
        });

        // Insert default values
        DB::table('order_statuses')->insert([
            ['name' => 'ready for washing'],
            ['name' => 'ready for ironing'],
            ['name' => 'ready for pickup'],
            ['name' => 'ready for delivery']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
};
