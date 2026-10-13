<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganizersController extends ResourceController
{
    protected string $modelClass = Organizer::class;
    protected string $resource = 'organizers';
    protected string $title = 'Organizer';
    protected array $columns = [
        ['key' => 'name', 'label' => 'Organizer'], ['key' => 'department', 'label' => 'Department'], ['key' => 'email', 'label' => 'Email'],
    ];
    protected array $details = [
        ['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'Email'], ['key' => 'phone', 'label' => 'Phone'],
        ['key' => 'department', 'label' => 'Department'], ['key' => 'bio', 'label' => 'About'],
    ];

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Full name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email address', 'type' => 'email', 'required' => true],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
            ['name' => 'department', 'label' => 'Department', 'type' => 'text'],
            ['name' => 'bio', 'label' => 'About the organizer', 'type' => 'textarea'],
        ];
    }

    protected function rules(Request $request, ?Model $record = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('organizers')->ignore($record?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'department' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ];
    }
}