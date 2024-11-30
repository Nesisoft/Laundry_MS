<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('created_at');
            $table->index('updated_at');

            // Define foreign key
        });

        // Insert initial data
        // DB::table('services')->insert([
        //     ['name' => 'Wash only'],
        //     ['name' => 'Iron only'],
        //     ['name' => 'Wash and Iron']
        // ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
