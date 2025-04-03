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
        Schema::create('app_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->index('name', 'app_services_name_idx1');
            $table->index('created_at', 'app_services_created_at_idx1');
            $table->index('updated_at', 'app_services_updated_at_idx1');
        });

        // Insert initial data
        DB::table('app_services')->insert([
            ['name' => 'Wash only'],
            ['name' => 'Iron only'],
            ['name' => 'Wash and Iron'],
            ['name' => 'Wash, Iron and Fold']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_services');
    }
};
