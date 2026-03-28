<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School ERP') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        body {
            background: #f1f5f9;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0f172a;
            position: fixed;
            color: #fff;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }

        .sidebar .active {
            background: #2563eb;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .topbar {
            background: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }
    </style>

    @livewireStyles
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="p-3 text-center">🎓 School ERP</h4>

    <a href="{{ route('dashboard') }}" class="active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="#">
        <i class="bi bi-people"></i> Students
    </a>

    <a href="#">
        <i class="bi bi-person-badge"></i> Teachers
    </a>

    <a href="#">
        <i class="bi bi-calendar-check"></i> Attendance
    </a>

    <a href="#">
        <i class="bi bi-cash-stack"></i> Fees
    </a>

    <a href="#">
        <i class="bi bi-file-earmark-text"></i> Reports
    </a>
</div>

<!-- Main Content -->
<div class="content">

    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center shadow-sm">
        <h5 class="mb-0">Dashboard</h5>

        <div class="d-flex align-items-center gap-3">
            <span>👤 {{ Auth::user()->name ?? 'Admin' }}</span>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <!-- Page Heading -->
    @if (isset($header))
        <div class="mb-3">
            {{ $header }}
        </div>
    @endif

    <!-- Page Content -->
    <div>
        {{ $slot }}
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts

</body>
</html>