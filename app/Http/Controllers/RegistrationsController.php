<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationsController extends ResourceController
{
    protected string $modelClass = Registration::class;
    protected string $resource = 'registrations';
    protected string $title = 'Registration';
    protected array $relations = ['event', 'participant'];
    protected array $columns = [
        ['key' => 'participant.full_name', 'label' => 'Participant'], ['key' => 'event.title', 'label' => 'Event'],
        ['key' => 'registered_at', 'label' => 'Registered'], ['key' => 'status', 'label' => 'Status'],
    ];
    protected array $details = [
        ['key' => 'participant.full_name', 'label' => 'Participant'], ['key' => 'event.title', 'label' => 'Event'],
        ['key' => 'registered_at', 'label' => 'Registration date'], ['key' => 'status', 'label' => 'Status'],
        ['key' => 'notes', 'label' => 'Notes'],
    ];

    protected function fields(): array
    {
        return [
            ['name' => 'event_id', 'label' => 'Event', 'type' => 'select', 'required' => true, 'options' => Event::orderBy('starts_at')->pluck('title', 'id')->all()],
            ['name' => 'participant_id', 'label' => 'Participant', 'type' => 'select', 'required' => true, 'options' => Participant::orderBy('full_name')->pluck('full_name', 'id')->all()],
            ['name' => 'registered_at', 'label' => 'Registration date', 'type' => 'date', 'required' => true],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => [
                'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled',
            ]],
            ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea'],
        ];
    }

    protected function rules(Request $request, ?Model $record = null): array
    {
        return [
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'participant_id' => [
                'required', 'integer', 'exists:participants,id',
                Rule::unique('registrations', 'participant_id')
                    ->where(fn ($query) => $query->where('event_id', $request->input('event_id')))
                    ->ignore($record?->id),
            ],
            'registered_at' => ['required', 'date'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}