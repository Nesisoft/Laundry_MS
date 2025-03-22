<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->enum('role', ['admin', 'manager', 'employee']);
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('archived', 'users_archived_idx1');
            $table->index('role', 'users_role_idx1');
            $table->index('username', 'users_username_idx1');
            $table->index('password', 'users_password_idx1');
            $table->index('created_at', 'users_created_at_idx1');
            $table->index('updated_at', 'users_updated_at_idx1');
        });

        // Insert default admin user
        DB::table('users')->insert([
            'role' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('123@Password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
