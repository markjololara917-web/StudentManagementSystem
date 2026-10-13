@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div><div class="eyebrow mb-2">Campus operations</div><h1 class="page-title mb-1">Event overview</h1><p class="muted mb-0">A live view of your campus events and registrations.</p></div>
    <a href="{{ route('events.create') }}" class="btn btn-primary">＋ Create event</a>
</div>
<div class="row row-cols-2 row-cols-lg-5 g-3 mb-4">
    @foreach ([['Events', 'events', 'events.index'], ['Organizers', 'organizers', 'organizers.index'], ['Venues', 'venues', 'venues.index'], ['Participants', 'participants', 'participants.index'], ['Registrations', 'registrations', 'registrations.index']] as [$label, $key, $route])
        <div class="col"><a href="{{ route($route) }}" class="text-decoration-none text-reset"><div class="stat"><div class="stat-label mb-2">{{ $label }}</div><div class="stat-number">{{ number_format($counts[$key]) }}</div></div></a></div>
    @endforeach
</div>
<div class="row g-4">
    <div class="col-xl-7">
        <section class="panel h-100">
            <div class="panel-heading d-flex align-items-center justify-content-between"><div><div class="eyebrow">Coming up</div><h2 class="h5 mb-0 mt-1">Upcoming events</h2></div><a class="small text-decoration-none" href="{{ route('events.index') }}">All events</a></div>
            <div class="table-responsive">
                <table class="table"><thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Status</th></tr></thead><tbody>
                    @forelse ($upcomingEvents as $event)
                        <tr><td><a class="fw-semibold text-decoration-none text-reset" href="{{ route('events.show', $event) }}">{{ $event->title }}</a><div class="small muted">{{ $event->organizer->name }}</div></td><td class="text-nowrap">{{ $event->starts_at->format('M j, Y') }}<div class="small muted">{{ $event->starts_at->format('g:i A') }}</div></td><td>{{ $event->venue->name }}</td><td><span class="badge badge-soft">{{ ucfirst($event->status) }}</span></td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center muted py-5">No upcoming events yet.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
        </section>
    </div>
    <div class="col-xl-5">
        <section class="panel h-100">
            <div class="panel-heading d-flex align-items-center justify-content-between"><div><div class="eyebrow">Latest activity</div><h2 class="h5 mb-0 mt-1">Recent registrations</h2></div><a class="small text-decoration-none" href="{{ route('registrations.index') }}">View all</a></div>
            <div class="list-group list-group-flush">
                @forelse ($recentRegistrations as $registration)
                    <a href="{{ route('registrations.show', $registration) }}" class="list-group-item list-group-item-action px-4 py-3"><div class="d-flex justify-content-between gap-3"><span class="fw-semibold">{{ $registration->participant->full_name }}</span><span class="badge badge-soft align-self-start">{{ ucfirst($registration->status) }}</span></div><div class="small muted mt-1">{{ $registration->event->title }} · {{ $registration->registered_at->format('M j, Y') }}</div></a>
                @empty
                    <div class="text-center muted py-5">No registrations yet.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection