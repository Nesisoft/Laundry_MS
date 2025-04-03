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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', ['percentage', 'amount'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->string('description')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();


            $table->index('code', 'discounts_code_idx1');
            $table->index('type', 'discounts_type_idx1');
            $table->index('value', 'discounts_value_idx1');
            $table->index('expiration_date', 'discounts_expiration_date_idx1');
            $table->index('archived', 'discounts_archived_idx1');
            $table->index('created_at', 'discounts_created_at_idx1');
            $table->index('updated_at', 'discounts_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
