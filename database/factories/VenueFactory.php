<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true).' Hall',
            'location' => fake()->city(),
            'capacity' => fake()->numberBetween(40, 800),
            'description' => fake()->sentence(),
        ];
    }
}