<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $organizers = Organizer::factory(5)->create();
        $venues = Venue::factory(5)->create();
        $participants = Participant::factory(40)->create();

        $events = collect();
        foreach (range(1, 10) as $index) {
            $events->push(Event::factory()->create([
                'organizer_id' => $organizers->random()->id,
                'venue_id' => $venues->random()->id,
            ]));
        }

        $events->each(function (Event $event) use ($participants) {
            $participants->random(8)->each(function (Participant $participant) use ($event) {
                Registration::factory()->create([
                    'event_id' => $event->id,
                    'participant_id' => $participant->id,
                ]);
            });
        });
    }
}
