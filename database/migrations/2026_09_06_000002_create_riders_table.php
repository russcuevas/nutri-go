<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('vehicle_type')->default('Motorcycle');
            $table->string('plate_number');
            $table->string('license_number');
            $table->string('license_image')->nullable();
            $table->string('phone');
            $table->string('barangay'); // Lipa City Barangay
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_online')->default(true);
            $table->integer('active_orders_count')->default(0);
            $table->integer('total_deliveries')->default(0);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
