@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div><div class="eyebrow mb-2">Record details</div><h1 class="page-title mb-0">{{ data_get($record, $details[0]['key']) ?: $title }}</h1></div>
    <div class="d-flex gap-2"><a href="{{ route($resource.'.edit', $record) }}" class="btn btn-primary">Edit {{ strtolower($title) }}</a><a href="{{ route($resource.'.index') }}" class="btn btn-outline-secondary">Back to list</a></div>
</div>
<section class="panel p-4 p-lg-5"><dl class="row mb-0">
    @foreach ($details as $detail)
        @php($value = data_get($record, $detail['key']))
        <dt class="col-sm-3 py-3 border-bottom muted">{{ $detail['label'] }}</dt>
        <dd class="col-sm-9 py-3 border-bottom mb-0">@if ($value instanceof \DateTimeInterface){{ $value->format('M j, Y g:i A') }}@else{{ $value ?: '—' }}@endif</dd>
    @endforeach
</dl></section>
@endsection