@extends('layouts.app')

@section('content')
<div class="mb-4"><div class="eyebrow mb-2">{{ $record ? 'Update record' : 'New record' }}</div><h1 class="page-title mb-0">{{ $record ? 'Edit' : 'Add' }} {{ strtolower($title) }}</h1></div>
<section class="panel p-4 p-lg-5">
    <form method="POST" action="{{ $record ? route($resource.'.update', $record) : route($resource.'.store') }}">
        @csrf
        @if ($record) @method('PUT') @endif
        <div class="row g-4">
            @foreach ($fields as $field)
                @php($value = old($field['name'], $values[$field['name']] ?? ''))
                <div class="col-md-{{ $field['type'] === 'textarea' ? '12' : '6' }}">
                    <label class="form-label" for="{{ $field['name'] }}">{{ $field['label'] }}@if ($field['required'] ?? false) <span class="text-danger">*</span>@endif</label>
                    @if ($field['type'] === 'textarea')
                        <textarea class="form-control @error($field['name']) is-invalid @enderror" id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="3" @required($field['required'] ?? false)>{{ $value }}</textarea>
                    @elseif ($field['type'] === 'select')
                        <select class="form-select @error($field['name']) is-invalid @enderror" id="{{ $field['name'] }}" name="{{ $field['name'] }}" @required($field['required'] ?? false)>
                            <option value="">Choose {{ strtolower($field['label']) }}</option>
                            @foreach ($field['options'] as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach
                        </select>
                    @else
                        <input class="form-control @error($field['name']) is-invalid @enderror" id="{{ $field['name'] }}" type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ $value }}" @required($field['required'] ?? false) @if(isset($field['min'])) min="{{ $field['min'] }}" @endif @if(isset($field['max'])) max="{{ $field['max'] }}" @endif>
                    @endif
                    @error($field['name'])<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </div>
        <div class="d-flex gap-2 mt-4"><button class="btn btn-primary">{{ $record ? 'Save changes' : 'Create '.$title }}</button><a href="{{ route($resource.'.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
    </form>
</section>
@endsection