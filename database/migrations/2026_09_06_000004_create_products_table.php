<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('calories')->default(0);
            $table->decimal('protein_g', 6, 2)->default(0);
            $table->decimal('carbs_g', 6, 2)->default(0);
            $table->decimal('fat_g', 6, 2)->default(0);
            $table->json('dietary_tags')->nullable(); // e.g. ["Vegan", "High-Protein", "Keto", "Diabetic-Friendly", "Low-Calorie", "Gluten-Free", "Low-Sodium"]
            $table->boolean('is_healthy_choice')->default(true);
            $table->boolean('is_supplement')->default(false);
            $table->unsignedBigInteger('alternative_to_id')->nullable();
            $table->text('healthy_alternative_notes')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('stock')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
