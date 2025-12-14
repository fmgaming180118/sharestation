<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pricePerKwh = fake()->randomFloat(2, 2.5, 8.0);
        $energyKwh = fake()->randomFloat(2, 5.0, 50.0);
        $startAt = fake()->dateTimeBetween('-1 week', '+1 week');
        $endAt = (clone $startAt)->modify('+' . rand(30, 180) . ' minutes');

        return [
            'user_id' => User::factory(),
            'station_id' => Station::factory(),
            'charging_slot_id' => null,
            'status' => fake()->randomElement([
                BookingStatus::PENDING->value,
                BookingStatus::CHARGING->value,
                BookingStatus::COMPLETED->value,
            ]),
            'battery_percentage' => fake()->numberBetween(20, 90),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'price_per_kwh' => $pricePerKwh,
            'energy_kwh' => $energyKwh,
            'amount' => round($pricePerKwh * $energyKwh, 2),
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::PENDING->value,
        ]);
    }

    /**
     * Indicate that the booking is charging.
     */
    public function charging(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::CHARGING->value,
        ]);
    }
}
