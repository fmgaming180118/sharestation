<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charging_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->string('slot_number');
            $table->enum('status', ['available', 'charging', 'maintenance'])->default('available');
            $table->timestamps();

            $table->unique(['station_id', 'slot_number']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charging_slots');
    }
};
