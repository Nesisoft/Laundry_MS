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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->unique();
            $table->string('value', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('key', 'businesses_key_idx1');
            $table->index('value', 'businesses_value_idx1');
            $table->index('created_at', 'businesses_created_at_idx1');
            $table->index('updated_at', 'businesses_updated_at_idx1');
        });

        // Insert default data
        $data = [
            'business_name' => NULL,
            'product_key' => NULL,
            'mode' => NULL,
            'branch_name' => NULL,
            'phone_number' => NULL,
            'email' => NULL,
            'logo' => NULL,
            'banner' => NULL,
            'motto' => NULL
        ];

        foreach ($data as $key => $value) {
            DB::table('businesses')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }

    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
