<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'department' => fake()->randomElement(['Student Affairs', 'Computer Studies', 'Arts and Sciences']),
            'bio' => fake()->sentence(),
        ];
    }
}