<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SmileCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1a237e 0%, #283593 50%, #1565c0 100%);
            z-index: 100;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar.collapsed {
            transform: translateX(-260px);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }

        .sidebar-brand .brand-name span {
            color: #80d8ff;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.2rem;
        }

        /* User card in sidebar */
        .sidebar-user {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #42a5f5, #80d8ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .user-info .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.55);
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 0.2rem;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .sidebar-nav a i {
            width: 18px;
            text-align: center;
            font-size: 0.95rem;
        }

        /* Logout at bottom */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-footer form button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            color: rgba(255,255,255,0.65);
            background: none;
            border: none;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-footer form button:hover {
            background: rgba(229, 57, 53, 0.2);
            color: #ef9a9a;
        }

        /* ===== TOP NAVBAR ===== */
        .topbar {
            position: fixed;
            top: 0; right: 0;
            left: 260px;
            height: 64px;
            background: white;
            border-bottom: 1px solid #e8edf2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 99;
            transition: left 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .topbar.full {
            left: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 8px;
            color: #546e7a;
            font-size: 1.1rem;
            transition: background 0.2s;
        }

        .toggle-btn:hover {
            background: #f0f4f8;
        }

        .topbar-title h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a237e;
        }

        .topbar-title p {
            font-size: 0.78rem;
            color: #90a4ae;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
            background: #f0f4f8;
            text-decoration: none;
        }

        .topbar-user .avatar-sm {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0288d1, #1565c0);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.8rem;
        }

        .topbar-user .user-name-sm {
            font-size: 0.85rem;
            font-weight: 600;
            color: #37474f;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            margin-top: 64px;
            padding: 1.75rem;
            min-height: calc(100vh - 64px);
            transition: margin-left 0.3s ease;
        }

        .main-content.full {
            margin-left: 0;
        }

        /* Flash messages */
        .flash-message {
            padding: 0.85rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .flash-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #43a047;
        }

        .flash-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #e53935;
        }

        .flash-warning {
            background: #fff3e0;
            color: #e65100;
            border-left: 4px solid #fb8c00;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-260px); }
            .sidebar.mobile-open { transform: translateX(0); }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: true }">
    @php
        $user = Auth::user();
        $role = $user->role ?? 'admin';
        $navigationRole = match ($role) {
            'admin_cabinet', 'admin' => 'admin',
            'medecin', 'dentiste' => 'medecin',
            'secretaire', 'assistant' => 'secretaire',
            'patient' => 'patient',
            'fournisseur' => 'fournisseur',
            'super_admin' => 'super_admin',
            default => 'admin',
        };

        $firstName = $user->prenom ?? explode(' ', trim((string)($user->name ?? 'U')))[0] ?? 'U';
        $lastName = $user->nom ?? trim(str_replace($firstName, '', (string)($user->name ?? '')));
        $initials = strtoupper(substr((string)$firstName, 0, 1) . substr((string)$lastName, 0, 1)) ?: 'U';

        $roleLabel = match($role) {
            'super_admin' => 'Super Administrateur',
            'admin_cabinet', 'admin' => 'Administrateur',
            'medecin', 'dentiste' => 'Médecin Dentiste',
            'secretaire', 'assistant' => 'Secrétaire',
            'patient' => 'Patient',
            'fournisseur' => 'Fournisseur',
            default => ucfirst(str_replace('_', ' ', $role)),
        };
    @endphp

    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'collapsed': !sidebarOpen }">
        <div class="sidebar-brand">
            <div class="brand-name">Smile<span>Care</span></div>
            <div class="brand-sub">Cabinet Dentaire Pro</div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">{{ $initials }}</div>
            <div class="user-info">
                <div class="user-name">{{ trim($firstName . ' ' . $lastName) }}</div>
                <div class="user-role">{{ $roleLabel }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul>
                @includeIf('partials.navigation.' . $navigationRole)
            </ul>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Top bar -->
    <header class="topbar" :class="{ 'full': !sidebarOpen }">
        <div class="topbar-left">
            <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-title">
                <h1>@yield('page-title', 'Tableau de bord')</h1>
                <p>@yield('page-subtitle', '')</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-user">
                <div class="avatar-sm">{{ $initials }}</div>
                <span class="user-name-sm">{{ trim($firstName . ' ' . $lastName) }}</span>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="main-content" :class="{ 'full': !sidebarOpen }">
        @if(session('success'))
            <div class="flash-message flash-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-error">
                <i class="fas fa-times-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="flash-message flash-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
