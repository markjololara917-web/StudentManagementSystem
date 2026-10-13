<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_resource_crud_work(): void
    {
        $this->get(route('dashboard'))->assertOk()->assertSee('Event overview');

        $this->post(route('organizers.store'), [
            'name' => 'Maya Santos',
            'email' => 'maya@example.test',
            'department' => 'Student Affairs',
        ])->assertRedirect(route('organizers.index'));

        $organizer = Organizer::where('email', 'maya@example.test')->firstOrFail();
        $this->assertDatabaseHas('organizers', ['id' => $organizer->id, 'name' => 'Maya Santos']);

        $this->put(route('organizers.update', $organizer), [
            'name' => 'Maya R. Santos',
            'email' => 'maya@example.test',
            'department' => 'Student Affairs',
        ])->assertRedirect(route('organizers.index'));
        $this->assertDatabaseHas('organizers', ['id' => $organizer->id, 'name' => 'Maya R. Santos']);

        $this->delete(route('organizers.destroy', $organizer))->assertRedirect(route('organizers.index'));
        $this->assertDatabaseMissing('organizers', ['id' => $organizer->id]);
    }

    public function test_event_and_registration_relationships_and_validation_work(): void
    {
        $organizer = Organizer::factory()->create();
        $venue = Venue::factory()->create();
        $participant = Participant::factory()->create();

        $this->post(route('events.store'), [
            'title' => 'Student Leadership Forum',
            'organizer_id' => $organizer->id,
            'venue_id' => $venue->id,
            'starts_at' => '2027-02-10T09:00',
            'ends_at' => '2027-02-10T12:00',
            'capacity' => 120,
            'status' => 'scheduled',
        ])->assertRedirect(route('events.index'));

        $event = Event::where('title', 'Student Leadership Forum')->firstOrFail();
        $this->assertSame($organizer->id, $event->organizer->id);
        $this->assertSame($venue->id, $event->venue->id);

        $registrationData = [
            'event_id' => $event->id,
            'participant_id' => $participant->id,
            'registered_at' => '2027-01-20',
            'status' => 'confirmed',
        ];

        $this->post(route('registrations.store'), $registrationData)->assertRedirect(route('registrations.index'));
        $registration = Registration::firstOrFail();
        $this->assertSame($event->id, $registration->event->id);
        $this->assertSame($participant->id, $registration->participant->id);
        $this->get(route('registrations.show', $registration))->assertOk()->assertSee('Student Leadership Forum');

        $this->from(route('registrations.create'))
            ->post(route('registrations.store'), $registrationData)
            ->assertRedirect(route('registrations.create'))
            ->assertSessionHasErrors('participant_id');

        $this->post(route('events.store'), [
            'title' => 'Invalid schedule',
            'organizer_id' => $organizer->id,
            'venue_id' => $venue->id,
            'starts_at' => '2027-02-10T12:00',
            'ends_at' => '2027-02-10T09:00',
            'capacity' => 120,
            'status' => 'scheduled',
        ])->assertSessionHasErrors('ends_at');
    }

    public function test_database_seeder_populates_all_five_related_tables(): void
    {
        $this->seed();

        $this->assertDatabaseCount('organizers', 5);
        $this->assertDatabaseCount('venues', 5);
        $this->assertDatabaseCount('events', 10);
        $this->assertDatabaseCount('participants', 40);
        $this->assertDatabaseCount('registrations', 80);
    }
}