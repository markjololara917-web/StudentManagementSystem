<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'participant_id' => Participant::factory(),
            'registered_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}