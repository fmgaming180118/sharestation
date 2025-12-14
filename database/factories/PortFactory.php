<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Port>
 */
class PortFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $powerKw = fake()->randomElement([50, 100, 150, 200]);
        
        return [
            'station_id' => Station::factory(),
            'name' => 'Port ' . strtoupper(fake()->randomLetter()),
            'type' => $powerKw >= 100 ? 'Fast Charging' : 'Regular Charging',
            'power_kw' => $powerKw,
            'price_per_kwh' => fake()->randomFloat(2, 2.5, 8.0),
            'status' => fake()->randomElement(['available', 'occupied', 'maintenance']),
        ];
    }

    /**
     * Indicate that the port is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
        ]);
    }

    /**
     * Indicate that the port is occupied.
     */
    public function occupied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'occupied',
        ]);
    }
}
