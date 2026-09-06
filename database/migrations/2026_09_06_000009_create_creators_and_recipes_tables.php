<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('channel_name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('specialties')->nullable(); // e.g. "Keto Chef, Calorie Counting, Plant-Based"
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        Schema::create('recipes_and_vlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('creators')->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->text('video_embed')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('prep_time_mins')->default(15);
            $table->integer('calories')->default(350);
            $table->decimal('protein_g', 6, 2)->default(25);
            $table->decimal('carbs_g', 6, 2)->default(30);
            $table->decimal('fat_g', 6, 2)->default(10);
            $table->json('ingredients')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_premium_only')->default(false);
            $table->json('linked_product_ids')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes_and_vlogs');
        Schema::dropIfExists('creators');
    }
};
