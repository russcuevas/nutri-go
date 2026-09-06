<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rider_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->unique()->constrained('riders')->onDelete('cascade');
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('pending_payout', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('rider_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('riders')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->enum('type', ['delivery_fee', 'tip', 'payout_deduction', 'adjustment'])->default('delivery_fee');
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('rider_payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_id')->constrained('riders')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('gcash_number');
            $table->string('gcash_account_name');
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->string('admin_reference_no')->nullable();
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('requested_at');
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rider_payout_requests');
        Schema::dropIfExists('rider_wallet_transactions');
        Schema::dropIfExists('rider_wallets');
    }
};
