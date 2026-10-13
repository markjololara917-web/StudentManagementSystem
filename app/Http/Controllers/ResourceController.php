<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class ResourceController extends Controller
{
    protected string $modelClass;
    protected string $resource;
    protected string $title;
    protected array $columns = [];
    protected array $details = [];
    protected array $relations = [];

    abstract protected function fields(): array;

    abstract protected function rules(Request $request, ?Model $record = null): array;

    public function index()
    {
        $records = ($this->modelClass)::query()
            ->with($this->relations)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('records.index', [
            'records' => $records,
            'columns' => $this->columns,
            'resource' => $this->resource,
            'title' => $this->title,
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        ($this->modelClass)::create($request->validate($this->rules($request)));

        return redirect()->route($this->resource.'.index')->with('success', $this->title.' created successfully.');
    }

    public function show(int $id)
    {
        $record = ($this->modelClass)::with($this->relations)->findOrFail($id);

        return view('records.show', [
            'record' => $record,
            'details' => $this->details,
            'resource' => $this->resource,
            'title' => $this->title,
        ]);
    }

    public function edit(int $id)
    {
        return $this->form(($this->modelClass)::findOrFail($id));
    }

    public function update(Request $request, int $id)
    {
        $record = ($this->modelClass)::findOrFail($id);
        $record->update($request->validate($this->rules($request, $record)));

        return redirect()->route($this->resource.'.index')->with('success', $this->title.' updated successfully.');
    }

    public function destroy(int $id)
    {
        try {
            ($this->modelClass)::findOrFail($id)->delete();
        } catch (QueryException) {
            throw ValidationException::withMessages([
                'record' => 'This record is linked to existing events or registrations and cannot be deleted yet.',
            ]);
        }

        return redirect()->route($this->resource.'.index')->with('success', $this->title.' deleted successfully.');
    }

    private function form(?Model $record = null)
    {
        $fields = $this->fields();
        $values = [];

        foreach ($fields as $field) {
            $value = $record?->getAttribute($field['name']);
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format($field['type'] === 'datetime-local' ? 'Y-m-d\TH:i' : 'Y-m-d');
            }
            $values[$field['name']] = $value;
        }

        return view('records.form', [
            'fields' => $fields,
            'values' => $values,
            'record' => $record,
            'resource' => $this->resource,
            'title' => $this->title,
        ]);
    }
}