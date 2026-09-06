<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('rider_id')->nullable()->constrained('riders')->onDelete('set null');
            $table->enum('status', [
                'pending_store',
                'store_accepted_preparing',
                'ready_for_pickup',
                'rider_assigned',
                'rider_picked_up',
                'on_the_way',
                'delivered',
                'declined_by_store',
                'cancelled'
            ])->default('pending_store');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(10.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['gcash', 'cod'])->default('gcash');
            $table->enum('payment_status', ['pending_verification', 'verified', 'failed'])->default('pending_verification');
            $table->string('payment_proof_image')->nullable();
            $table->string('payment_reference_no')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('delivery_barangay'); // Lipa City Barangay
            $table->text('delivery_address');
            $table->string('delivery_landmark')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->decimal('delivery_latitude', 10, 7)->default(13.9419);
            $table->decimal('delivery_longitude', 10, 7)->default(121.1631);
            $table->decimal('distance_km', 6, 2)->default(1.5);
            $table->enum('delivery_type', ['immediate', 'scheduled'])->default('immediate');
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('estimated_prep_time_mins')->default(20);
            $table->integer('estimated_delivery_time_mins')->default(15);
            $table->text('store_decline_reason')->nullable();
            $table->string('rider_proof_image')->nullable();
            $table->dateTime('rider_delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
