<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('store_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('barangay'); // Lipa City Barangay
            $table->text('address_line');
            $table->decimal('latitude', 10, 7)->default(13.9419);
            $table->decimal('longitude', 10, 7)->default(121.1631);
            $table->string('phone');
            $table->string('health_category')->default('Organic & Salads');
            $table->string('business_permit_no')->nullable();
            $table->string('health_certificate')->nullable();
            $table->string('gcash_name')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('gcash_qr')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->decimal('commission_percent', 5, 2)->default(10.00);
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('20:00:00');
            $table->boolean('is_open')->default(true);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
