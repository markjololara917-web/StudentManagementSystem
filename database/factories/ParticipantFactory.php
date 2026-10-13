<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'student_id' => fake()->unique()->numerify('2026-####'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'course' => fake()->randomElement(['BS Information Technology', 'BS Computer Science', 'BA Communication', 'BS Business Administration']),
            'year_level' => fake()->numberBetween(1, 4),
        ];
    }
}