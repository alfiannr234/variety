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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_sub_category_id')->constrained('sub_sub_categories')->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('price');
            $table->integer('original_price')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->integer('stock')->default(0);

            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->integer('sold_count')->default(0);

            $table->boolean('is_flash_sale')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_featured')->default(false);

            $table->string('image');
            $table->json('images')->nullable();
            $table->json('specifications')->nullable();
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->json('tags')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
