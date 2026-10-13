<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} | Campus Events</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --ink:#172522; --muted:#71817c; --line:#e5ece8; --paper:#f5f8f5; --green:#216b56; --mint:#dcefe5; --coral:#e87556; }
        body { background:var(--paper); color:var(--ink); font-family:'DM Sans',sans-serif; }
        h1,h2,h3,h4,.brand { font-family:'Space Grotesk',sans-serif; letter-spacing:0; }
        .shell { min-height:100vh; }
        .sidebar { width:248px; background:#183b32; color:#e8f1ec; }
        .brand { font-size:1.22rem; font-weight:700; color:white; text-decoration:none; }
        .brand-mark { display:inline-grid; place-items:center; width:34px; height:34px; margin-right:10px; background:#e87556; color:#fff; border-radius:9px; font-weight:700; }
        .nav-label { margin:30px 12px 9px; color:#9ebbb0; font-size:.68rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
        .sidebar .nav-link { color:#c3d7ce; border-radius:7px; padding:10px 12px; margin:2px 0; font-size:.93rem; }
        .sidebar .nav-link:hover,.sidebar .nav-link.active { background:#2b5548; color:#fff; }
        .main { min-width:0; }
        .topbar { min-height:68px; background:#fff; border-bottom:1px solid var(--line); }
        .content { max-width:1420px; margin:auto; padding:34px clamp(18px,4vw,52px); }
        .eyebrow { color:var(--green); font-size:.73rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
        .page-title { font-size:clamp(1.65rem,3vw,2.15rem); font-weight:700; }
        .panel,.stat { background:#fff; border:1px solid var(--line); border-radius:8px; }
        .stat { padding:20px; height:100%; }
        .stat-number { font:700 1.8rem 'Space Grotesk',sans-serif; }
        .stat-label { color:var(--muted); font-size:.88rem; }
        .panel-heading { padding:19px 22px; border-bottom:1px solid var(--line); }
        .table { --bs-table-bg:transparent; margin:0; }
        .table thead th { padding:12px 18px; color:var(--muted); font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; border-bottom-color:var(--line); white-space:nowrap; }
        .table tbody td { padding:15px 18px; vertical-align:middle; border-bottom-color:#edf1ee; }
        .btn-primary { --bs-btn-bg:var(--green); --bs-btn-border-color:var(--green); --bs-btn-hover-bg:#174d3e; --bs-btn-hover-border-color:#174d3e; }
        .btn-outline-secondary { --bs-btn-color:#52645d; --bs-btn-border-color:#d5dfd9; }
        .badge-soft { background:var(--mint); color:#216b56; font-weight:600; }
        .form-label { font-weight:600; font-size:.88rem; }
        .form-control,.form-select { border-color:#d9e2dc; padding:.68rem .8rem; }
        .form-control:focus,.form-select:focus { border-color:#5a9b82; box-shadow:0 0 0 .2rem #216b561c; }
        .muted { color:var(--muted); }
        @media (max-width:767.98px) { .sidebar { width:100%; } .sidebar-inner { padding:12px 16px !important; } .sidebar .nav-label { display:none; } .sidebar nav { display:flex; flex-direction:row; overflow:auto; gap:4px; margin-top:10px; } .sidebar .nav-link { white-space:nowrap; } .content { padding-top:24px; } }
    </style>
</head>
<body>
<div class="shell d-flex flex-column flex-md-row">
    <aside class="sidebar flex-shrink-0">
        <div class="sidebar-inner p-4">
            <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">C</span>Campus Events</a>
            <div class="nav-label">Workspace</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Overview</a>
            </nav>
            <div class="nav-label">Management</div>
            <nav class="nav flex-column">
                @foreach (['events' => 'Events', 'organizers' => 'Organizers', 'venues' => 'Venues', 'participants' => 'Participants', 'registrations' => 'Registrations'] as $routeName => $label)
                    <a class="nav-link {{ request()->routeIs($routeName.'.*') ? 'active' : '' }}" href="{{ route($routeName.'.index') }}">{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </aside>
    <main class="main flex-grow-1">
        <header class="topbar d-flex align-items-center justify-content-between px-4 px-lg-5">
            <span class="small muted">Student event management</span>
            <span class="small fw-semibold">Campus Admin</span>
        </header>
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->has('record'))
                <div class="alert alert-danger">{{ $errors->first('record') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>