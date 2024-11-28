<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotional_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description')->nullable();
            $table->enum('apply_to', ['all', 'specific'])->default('all');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount', 10, 2);
            $table->date('expiration_date')->nullable();
            $table->timestamps();

            $table->index('code', 'promotional_codes_code_idx1');
            $table->index('discount_type', 'promotional_codes_discount_type_idx1');
            $table->index('discount', 'promotional_codes_discount_idx1');
            $table->index('expiration_date', 'promotional_codes_expiration_date_idx1');
            $table->index('created_at', 'promotional_codes_created_at_idx1');
            $table->index('updated_at', 'promotional_codes_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotional_codes');
    }
};
