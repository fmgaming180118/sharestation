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
        Schema::table('stations', function (Blueprint $table) {
            // Remove old fields that will be moved to ports table
            if (Schema::hasColumn('stations', 'price_per_kwh')) {
                $table->dropColumn('price_per_kwh');
            }
            if (Schema::hasColumn('stations', 'power_kw')) {
                $table->dropColumn('power_kw');
            }
            
            // Add new fields
            $table->string('operational_hours')->default('09.00 - 23.00')->after('longitude');
            $table->boolean('is_open')->default(true)->after('operational_hours');
            $table->json('amenities')->nullable()->after('is_open');
            $table->string('image')->nullable()->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn(['operational_hours', 'is_open', 'amenities', 'image']);
            $table->decimal('price_per_kwh', 8, 2)->after('longitude');
            $table->unsignedSmallInteger('power_kw')->default(0)->after('price_per_kwh');
        });
    }
};
