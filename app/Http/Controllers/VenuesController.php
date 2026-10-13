<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VenuesController extends ResourceController
{
    protected string $modelClass = Venue::class;
    protected string $resource = 'venues';
    protected string $title = 'Venue';
    protected array $columns = [
        ['key' => 'name', 'label' => 'Venue'], ['key' => 'location', 'label' => 'Location'], ['key' => 'capacity', 'label' => 'Capacity'],
    ];
    protected array $details = [
        ['key' => 'name', 'label' => 'Venue name'], ['key' => 'location', 'label' => 'Location'],
        ['key' => 'capacity', 'label' => 'Capacity'], ['key' => 'description', 'label' => 'Description'],
    ];

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Venue name', 'type' => 'text', 'required' => true],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'required' => true],
            ['name' => 'capacity', 'label' => 'Capacity', 'type' => 'number', 'required' => true, 'min' => 1],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
        ];
    }

    protected function rules(Request $request, ?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('venues')->ignore($record?->id)],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}