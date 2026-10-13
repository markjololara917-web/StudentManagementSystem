<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipantsController extends ResourceController
{
    protected string $modelClass = Participant::class;
    protected string $resource = 'participants';
    protected string $title = 'Participant';
    protected array $columns = [
        ['key' => 'full_name', 'label' => 'Participant'], ['key' => 'student_id', 'label' => 'Student ID'],
        ['key' => 'course', 'label' => 'Course'], ['key' => 'year_level', 'label' => 'Year'],
    ];
    protected array $details = [
        ['key' => 'full_name', 'label' => 'Full name'], ['key' => 'student_id', 'label' => 'Student ID'],
        ['key' => 'email', 'label' => 'Email'], ['key' => 'phone', 'label' => 'Phone'],
        ['key' => 'course', 'label' => 'Course'], ['key' => 'year_level', 'label' => 'Year level'],
    ];

    protected function fields(): array
    {
        return [
            ['name' => 'full_name', 'label' => 'Full name', 'type' => 'text', 'required' => true],
            ['name' => 'student_id', 'label' => 'Student ID', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email address', 'type' => 'email', 'required' => true],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
            ['name' => 'course', 'label' => 'Course or program', 'type' => 'text', 'required' => true],
            ['name' => 'year_level', 'label' => 'Year level', 'type' => 'number', 'required' => true, 'min' => 1, 'max' => 8],
        ];
    }

    protected function rules(Request $request, ?Model $record = null): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:50', Rule::unique('participants')->ignore($record?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('participants')->ignore($record?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'course' => ['required', 'string', 'max:120'],
            'year_level' => ['required', 'integer', 'between:1,8'],
        ];
    }
}