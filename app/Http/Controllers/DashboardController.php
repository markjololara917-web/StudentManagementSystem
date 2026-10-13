<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\Venue;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'counts' => [
                'events' => Event::count(),
                'organizers' => Organizer::count(),
                'venues' => Venue::count(),
                'participants' => Participant::count(),
                'registrations' => Registration::count(),
            ],
            'upcomingEvents' => Event::with(['organizer', 'venue'])
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->limit(5)
                ->get(),
            'recentRegistrations' => Registration::with(['event', 'participant'])
                ->latest()
                ->limit(6)
                ->get(),
        ]);
    }
}