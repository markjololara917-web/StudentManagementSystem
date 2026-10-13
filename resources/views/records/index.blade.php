@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div><div class="eyebrow mb-2">Management</div><h1 class="page-title mb-0">{{ $title }}s</h1></div>
    <a href="{{ route($resource.'.create') }}" class="btn btn-primary">＋ Add {{ strtolower($title) }}</a>
</div>
<section class="panel">
    <div class="panel-heading d-flex align-items-center justify-content-between"><h2 class="h6 mb-0">All {{ strtolower($title) }}s</h2><span class="small muted">{{ $records->total() }} total</span></div>
    @if ($records->isEmpty())
        <div class="text-center py-5"><p class="muted mb-3">No {{ strtolower($title) }}s have been added yet.</p><a href="{{ route($resource.'.create') }}" class="btn btn-outline-primary">Add the first {{ strtolower($title) }}</a></div>
    @else
        <div class="table-responsive">
            <table class="table"><thead><tr>@foreach ($columns as $column)<th>{{ $column['label'] }}</th>@endforeach<th class="text-end">Actions</th></tr></thead><tbody>
                @foreach ($records as $record)
                    <tr>
                        @foreach ($columns as $column)
                            @php($value = data_get($record, $column['key']))
                            <td>@if ($value instanceof \DateTimeInterface){{ $value->format('M j, Y g:i A') }}@elseif ($column['key'] === 'status')<span class="badge badge-soft">{{ ucfirst($value ?? '') }}</span>@else{{ $value ?: '—' }}@endif</td>
                        @endforeach
                        <td class="text-end text-nowrap"><a href="{{ route($resource.'.show', $record) }}" class="btn btn-sm btn-outline-secondary">View</a> <a href="{{ route($resource.'.edit', $record) }}" class="btn btn-sm btn-outline-secondary">Edit</a> <form class="d-inline" method="POST" action="{{ route($resource.'.destroy', $record) }}" onsubmit="return confirm('Delete this record? This may also remove related registrations.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
                    </tr>
                @endforeach
            </tbody></table>
        </div>
        <div class="p-3">{{ $records->links() }}</div>
    @endif
</section>
@endsection