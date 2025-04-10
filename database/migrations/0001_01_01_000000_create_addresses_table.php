<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Polymorphic relationship columns
            $table->unsignedBigInteger('addressable_id')->nullable();
            $table->string('addressable_type')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('street', 'addresses_street_idx1');
            $table->index('city', 'addresses_city_idx1');
            $table->index('state', 'addresses_state_idx1');
            $table->index('zip_code', 'addresses_zip_code_idx1');
            $table->index('country', 'addresses_country_idx1');
            $table->index('latitude', 'addresses_latitude_idx1');
            $table->index('longitude', 'addresses_longitude_idx1');
            $table->index('created_at', 'addresses_created_at_idx1');
            $table->index('updated_at', 'addresses_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
