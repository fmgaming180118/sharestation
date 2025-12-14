<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->foreignId('charging_slot_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(BookingStatus::PENDING->value);
            $table->unsignedTinyInteger('battery_percentage')->default(0);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->decimal('price_per_kwh', 8, 2);
            $table->decimal('energy_kwh', 8, 3)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['station_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
