<?php

namespace Database\Factories;

use App\Models\Port;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 month', 'now');
        $startTime = (clone $date)->setTime(rand(7, 20), rand(0, 59));
        $durationMinutes = rand(30, 180);
        $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");
        $totalKwh = fake()->randomFloat(2, 5.0, 50.0);
        $pricePerKwh = fake()->randomFloat(2, 2.5, 8.0);

        return [
            'transaction_code' => 'TRF' . now()->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
            'station_id' => Station::factory(),
            'port_id' => Port::factory(),
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
            'total_kwh' => $totalKwh,
            'total_price' => round($totalKwh * $pricePerKwh, 2),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
            'confirmation_code' => Str::random(12),
        ];
    }

    /**
     * Indicate that the transaction is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
        ]);
    }
}
