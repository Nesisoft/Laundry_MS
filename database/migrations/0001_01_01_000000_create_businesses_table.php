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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->unique();
            $table->string('value', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('key', 'business_key_idx1');
            $table->index('value', 'business_value_idx1');
            $table->index('created_at', 'business_created_at_idx1');
            $table->index('updated_at', 'business_updated_at_idx1');
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
            DB::table('business')->insert([
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
