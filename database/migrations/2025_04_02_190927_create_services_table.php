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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();

            $table->index('name', 'services_name_idx1');
            $table->index('archived', 'services_archived_idx1');
            $table->index('created_at', 'services_created_at_idx1');
            $table->index('updated_at', 'services_updated_at_idx1');
        });

        // Insert initial data
        DB::table('services')->insert([
            ['name' => 'Wash only', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Iron only', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wash and Iron', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wash, Iron and Fold', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
