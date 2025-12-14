<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company() . ' Charging Station',
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'latitude' => fake()->latitude(-8.0, -6.0), // Indonesia region
            'longitude' => fake()->longitude(106.0, 115.0),
            'operational_hours' => '07:00 - 22:00',
            'is_open' => fake()->boolean(80), // 80% chance of being open
            'amenities' => json_encode(fake()->randomElements(['WiFi', 'Restroom', 'Cafe', 'Parking'], rand(1, 3))),
            'image' => null,
            'phone' => fake()->phoneNumber(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the station is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the station is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_open' => false,
        ]);
    }
}
