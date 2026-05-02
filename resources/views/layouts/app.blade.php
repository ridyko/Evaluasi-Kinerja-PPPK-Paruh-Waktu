<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ config('app.organization_name') }}</title>
    <meta name="description" content="{{ config('app.organization_slogan') }} {{ config('app.organization_name') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --accent: #06b6d4;
            --surface: #0f172a;
            --surface-2: #1e293b;
            --surface-3: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body {
            background: var(--surface);
            color: var(--text-primary);
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            border-right: 1px solid rgba(99, 102, 241, 0.1);
            width: 280px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .sidebar-logo .logo-text {
            font-weight: 700;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #c7d2fe, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-logo .logo-sub {
            font-size: 0.7rem;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
        }

        .nav-section {
            padding: 1rem 0.75rem;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(99, 102, 241, 0.08);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(6, 182, 212, 0.08));
            color: var(--primary-light);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Main content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.08);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-size: 1.15rem;
            font-weight: 600;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
        }

        .user-info {
            text-align: right;
        }

        .user-info .user-name {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-info .user-role {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: capitalize;
        }

        /* Content area */
        .content-area {
            padding: 1.5rem 2rem;
        }

        /* Cards */
        .card {
            background: var(--surface-2);
            border: 1px solid rgba(99, 102, 241, 0.08);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(99, 102, 241, 0.15);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2 {
            font-size: 1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats cards */
        .stat-card {
            background: var(--surface-2);
            border: 1px solid rgba(99, 102, 241, 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }

        .stat-card.purple::before { background: linear-gradient(90deg, #6366f1, #8b5cf6); }
        .stat-card.cyan::before { background: linear-gradient(90deg, #06b6d4, #22d3ee); }
        .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .stat-card.purple .stat-icon { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
        .stat-card.cyan .stat-icon { background: rgba(6, 182, 212, 0.15); color: #22d3ee; }
        .stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .stat-card.amber .stat-icon { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            border-bottom: 1px solid rgba(99, 102, 241, 0.08);
            text-align: left;
        }

        .data-table tbody td {
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.04);
            color: var(--text-primary);
        }

        .data-table tbody tr {
            transition: background 0.15s ease;
        }

        .data-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.04);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
            color: #fff;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);
            color: #fff;
        }

        .btn-ghost {
            background: rgba(99, 102, 241, 0.08);
            color: var(--text-primary);
            border: 1px solid rgba(99, 102, 241, 0.15);
        }

        .btn-ghost:hover {
            background: rgba(99, 102, 241, 0.15);
            color: #fff;
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        .badge-info {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 1rem;
            background: var(--surface);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.65rem 1rem;
            background: var(--surface);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: all 0.2s ease;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 1rem) center;
            padding-right: 2.5rem;
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Grid */
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }

        /* Actions */
        .actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .page-header p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .grid-4, .grid-3, .grid-2 { grid-template-columns: 1fr; }
            .content-area { padding: 1rem; }
            .topbar { padding: 0.75rem 1rem; }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--surface-3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* Mobile menu toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.25rem;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .mobile-toggle { display: block; }
        }

        /* Sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body class="h-full">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">{{ substr(config('app.name'), 0, 2) }}</div>
            <div>
                <div class="logo-text">{{ config('app.name') }}</div>
                <div class="logo-sub">{{ config('app.organization_name') }}</div>
            </div>
        </div>

        <nav class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-section-title" style="margin-top: 1rem;">Master Data</div>
            <a href="{{ route('jabatan.index') }}" class="nav-item {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Jabatan
            </a>
            <a href="{{ route('pegawai.index') }}" class="nav-item {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Pegawai
            </a>
            <a href="{{ route('penilai.index') }}" class="nav-item {{ request()->routeIs('penilai.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i> Pejabat Penilai
            </a>
            <a href="{{ route('indikator.index') }}" class="nav-item {{ request()->routeIs('indikator.*') ? 'active' : '' }}">
                <i class="fas fa-bullseye"></i> Indikator Kinerja
            </a>

            <div class="nav-section-title" style="margin-top: 1rem;">Manajemen Akun</div>
            <a href="{{ route('akun.index') }}" class="nav-item {{ request()->routeIs('akun.*') ? 'active' : '' }}">
                <i class="fas fa-user-lock"></i> Kelola Akun
            </a>
            @endif

            @if(auth()->user()->isPegawai())
            <div class="nav-section-title" style="margin-top: 1rem;">Menu Pegawai</div>
            <a href="{{ route('evaluasi.index') }}" class="nav-item {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Evaluasi Saya
            </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isPenilai())
            <div class="nav-section-title" style="margin-top: 1rem;">Evaluasi</div>
            <a href="{{ route('evaluasi.index') }}" class="nav-item {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i> Evaluasi Bulanan
            </a>
            @endif

            <div class="nav-section-title" style="margin-top: 1rem;">Akun</div>
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Pengaturan Akun
            </a>
        </nav>

        {{-- Sidebar footer --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 1rem 0.75rem; border-top: 1px solid rgba(99, 102, 241, 0.08);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; cursor: pointer; color: var(--text-secondary);">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Main content --}}
    <main class="main-content">
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            </div>
        </header>

        <div class="content-area">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>

    @yield('scripts')
</body>
</html>
