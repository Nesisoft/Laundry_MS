<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('name', 'app_statuses_name_idx1');
            $table->index('created_at', 'app_statuses_created_at_idx1');
            $table->index('updated_at', 'app_statuses_updated_at_idx1');
        });

        // Insert default values
        DB::table('app_statuses')->insert([
            ['name' => 'ready for washing'],
            ['name' => 'ready for ironing'],
            ['name' => 'ready for pickup'],
            ['name' => 'ready for delivery']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_statuses');
    }
};
