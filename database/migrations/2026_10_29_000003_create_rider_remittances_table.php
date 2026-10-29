<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rider_remittances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('riders')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->string('cycle_key'); // e.g., 2026-10-1 or 2026-10-2
            $table->decimal('total_collected_cash', 10, 2)->default(0.00);
            $table->decimal('total_remit_amount', 10, 2)->default(0.00); // sum of store food sales (subtotal)
            $table->decimal('rider_delivery_fees', 10, 2)->default(0.00);
            $table->unsignedInteger('orders_count')->default(0);
            $table->enum('status', ['unsubmitted', 'pending', 'verified', 'rejected'])->default('unsubmitted');
            $table->string('payment_method')->default('gcash');
            $table->string('reference_no')->nullable();
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->dateTime('remitted_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();

            $table->index(['rider_id', 'cycle_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rider_remittances');
    }
};

