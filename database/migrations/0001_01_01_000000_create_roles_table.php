<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();

            $table->index('name', 'roles_name_idx1');
        });

        DB::table('roles')->insert([
            ['name'=>'admin'],
            ['name'=>'manager'],
            ['name'=>'staff'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
