<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained('riders')->onDelete('set null');
            $table->integer('store_rating')->default(5);
            $table->text('store_comment')->nullable();
            $table->integer('rider_rating')->default(5);
            $table->text('rider_comment')->nullable();
            $table->string('health_satisfaction')->nullable(); // e.g. "Fresh & Healthy", "Accurate Calories", "Great Portion"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
