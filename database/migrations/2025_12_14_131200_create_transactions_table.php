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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // e.g., "TRF20251209"
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The Driver
            $table->foreignId('station_id')->constrained()->cascadeOnDelete(); // The Station
            $table->foreignId('port_id')->nullable()->constrained()->cascadeOnDelete(); // The Port used
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('total_kwh', 10, 3)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('confirmation_code')->nullable(); // e.g., "Je092jej20jK"
            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
            $table->index(['station_id', 'date']);
            $table->index('transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
