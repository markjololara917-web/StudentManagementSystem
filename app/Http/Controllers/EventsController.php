<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class EventsController extends ResourceController
{
    protected string $modelClass = Event::class;
    protected string $resource = 'events';
    protected string $title = 'Event';
    protected array $relations = ['organizer', 'venue'];
    protected array $columns = [
        ['key' => 'title', 'label' => 'Event'], ['key' => 'starts_at', 'label' => 'Date and time'],
        ['key' => 'venue.name', 'label' => 'Venue'], ['key' => 'status', 'label' => 'Status'],
    ];
    protected array $details = [
        ['key' => 'title', 'label' => 'Event title'], ['key' => 'organizer.name', 'label' => 'Organizer'],
        ['key' => 'venue.name', 'label' => 'Venue'], ['key' => 'description', 'label' => 'Description'],
        ['key' => 'starts_at', 'label' => 'Starts'], ['key' => 'ends_at', 'label' => 'Ends'],
        ['key' => 'capacity', 'label' => 'Capacity'], ['key' => 'status', 'label' => 'Status'],
    ];

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Event title', 'type' => 'text', 'required' => true],
            ['name' => 'organizer_id', 'label' => 'Organizer', 'type' => 'select', 'required' => true, 'options' => Organizer::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'venue_id', 'label' => 'Venue', 'type' => 'select', 'required' => true, 'options' => Venue::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'starts_at', 'label' => 'Starts at', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'ends_at', 'label' => 'Ends at', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'capacity', 'label' => 'Capacity', 'type' => 'number', 'required' => true, 'min' => 1],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => [
                'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled',
            ]],
        ];
    }

    protected function rules(Request $request, ?Model $record = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'organizer_id' => ['required', 'integer', 'exists:organizers,id'],
            'venue_id' => ['required', 'integer', 'exists:venues,id'],
            'description' => ['nullable', 'string', 'max:3000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,completed,cancelled'],
        ];
    }
}