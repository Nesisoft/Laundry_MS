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
        Schema::create('local_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->unique();
            $table->string('value', 255)->nullable();
            $table->timestamps();

            // Define indexes
            $table->index('key', 'local_configs_key_idx1');
            $table->index('value', 'local_configs_value_idx1');
            $table->index('created_at', 'local_configs_created_at_idx1');
            $table->index('updated_at', 'local_configs_updated_at_idx1');
        });

        // Insert default data
        $data = [
            'business_name' => NULL,
            'product_key' => NULL,
            'mode' => 'LOCAL',
            'branch_name' => NULL,
            'phone_number' => NULL,
            'email' => NULL,
            'logo' => NULL,
            'banner' => NULL,
            'motto' => NULL
        ];

        foreach ($data as $key => $value) {
            DB::table('local_configs')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_configs');
    }
};
