<?php

namespace Database\Factories;

use App\Models\Organizer;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('+1 week', '+6 months');

        return [
            'organizer_id' => Organizer::factory(),
            'venue_id' => Venue::factory(),
            'title' => fake()->randomElement(['Innovation Summit', 'Campus Arts Night', 'Career Workshop', 'Student Research Forum']),
            'description' => fake()->paragraph(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 hours'),
            'capacity' => fake()->numberBetween(30, 500),
            'status' => 'scheduled',
        ];
    }
}