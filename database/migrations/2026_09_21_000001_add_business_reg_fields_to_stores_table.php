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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('business_registration_number')->nullable()->after('business_permit_no');
            $table->string('tax_identification_number')->nullable()->after('business_registration_number');
            $table->date('business_establishment_date')->nullable()->after('tax_identification_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'business_registration_number',
                'tax_identification_number',
                'business_establishment_date',
            ]);
        });
    }
};
