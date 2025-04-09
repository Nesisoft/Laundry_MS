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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            $table->index('name', 'roles_name_idx1');
            $table->index('created_at', 'roles_created_at_idx1');
            $table->index('updated_at', 'roles_created_at_idx1');
        });

        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'employee'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
