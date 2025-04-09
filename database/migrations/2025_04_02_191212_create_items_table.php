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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->enum('category', ['baby', 'kid', 'adult', 'small', 'medium', 'large', 'xlarge', 'single', 'double', 'normal', 'queen', 'king'])->default('normal');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('image')->nullable();  // New image column
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('name', 'items_name_idx1');
            $table->index('image', 'items_image_idx1');
            $table->index('archived', 'items_archived_idx1');
            $table->index('created_at', 'items_created_at_idx1');
            $table->index('updated_at', 'items_updated_at_idx1');
        });

        DB::table('items')->insert([
            ['name' => 'Shirt', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trousers', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jeans', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Skirt', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blouse', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jacket', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suit', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sweater', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dress', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'T-Shirt', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shorts', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bedsheet', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Duvet', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pillow Case', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Curtain', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blanket', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tie', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Scarf', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Underwear', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shoe', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Socks', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apron', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab Coat', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chef Jacket', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Overalls', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gym Shorts', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Workout Top', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Yoga Pants', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Football Jersey', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Raincoat', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Winter Coat', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swimsuit', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bra', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hoodie', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'School Uniform', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Work Uniform', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tracksuit', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vest', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sleeveless Top', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pajama Top', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pajama Bottom', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nightgown', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baby Blanket', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baby Onesie', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Baby Bib', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Crib Sheet', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bathrobe', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hand Towel', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bath Towel', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Face Towel', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Table Cloth', 'amount' => 0.00, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
