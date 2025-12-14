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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Port A", "Port B"
            $table->enum('type', ['Fast Charging', 'Regular Charging'])->default('Regular Charging');
            $table->unsignedInteger('power_kw')->default(50); // e.g., 150kW
            $table->decimal('price_per_kwh', 10, 2)->default(1500); // e.g., Rp 2,500/kWh
            $table->enum('status', ['available', 'busy', 'maintenance'])->default('available');
            $table->timestamps();

            $table->index(['station_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
