<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SmileCaire</title>

    <link rel="icon" type="image/png" href="{{ asset('images/dental-icon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full" x-data="{ sidebarOpen: true, darkMode: false }" :class="{ 'dark': darkMode }">
    @php
        $user = Auth::user();
        $role = $user->role ?? 'admin';

        $dashboardRouteByRole = [
            'super_admin' => 'super_admin.dashboard',
            'admin_cabinet' => 'admin.dashboard',
            'medecin' => 'medecin.dashboard',
            'secretaire' => 'secretaire.dashboard',
            'patient' => 'patient.dashboard',
            'fournisseur' => 'fournisseur.dashboard',
            'admin' => 'admin.dashboard',
            'dentiste' => 'medecin.dashboard',
            'assistant' => 'secretaire.dashboard',
        ];

        $dashboardRoute = $dashboardRouteByRole[$role] ?? 'dashboard';

        $navigationRole = match ($role) {
            'admin_cabinet', 'admin' => 'admin',
            'medecin', 'dentiste' => 'medecin',
            'secretaire', 'assistant' => 'secretaire',
            'patient' => 'patient',
            'fournisseur' => 'fournisseur',
            'super_admin' => 'super_admin',
            default => 'admin',
        };

        $firstName = $user->prenom ?? explode(' ', trim((string)($user->name ?? 'Utilisateur')))[0] ?? 'Utilisateur';
        $lastName = $user->nom ?? trim(str_replace($firstName, '', (string)($user->name ?? '')));

        $initials = strtoupper(substr((string)$firstName, 0, 1) . substr((string)$lastName, 0, 1));
        if ($initials === '') {
            $initials = 'U';
        }
    @endphp

    <div class="min-h-full">
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 fixed top-0 z-30 w-full">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <a href="{{ route($dashboardRoute) }}" class="flex ml-2 md:mr-24">
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                                <span class="text-blue-600">Smile</span>Caire
                            </span>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <button @click="darkMode = !darkMode" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @includeIf('partials.notifications-list')
                                </div>
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
                                    {{ $initials }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ trim($firstName . ' ' . $lastName) }}
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                                <div class="py-2">
                                    @if(Route::has($role . '.profil'))
                                        <a href="{{ route($role . '.profil') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-user mr-2"></i> Mon profil
                                        </a>
                                    @endif
                                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Deconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <aside class="fixed top-0 left-0 z-20 w-64 h-full pt-16 transition-transform bg-white border-r border-gray-200 dark:bg-gray-900 dark:border-gray-700" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="h-full px-3 pb-4 overflow-y-auto">
                <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-800 dark:to-gray-700 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-cyan-600 flex items-center justify-center text-white font-bold">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ trim($firstName . ' ' . $lastName) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $role)) }}</p>
                        </div>
                    </div>
                </div>

                <ul class="space-y-2 font-medium">
                    @includeIf('partials.navigation.' . $navigationRole)
                </ul>
            </div>
        </aside>

        <div class="p-4 pt-20 transition-all duration-300" :class="{ 'lg:ml-64': sidebarOpen, 'lg:ml-0': !sidebarOpen }">
            <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('page-title')</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">@yield('page-subtitle')</p>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
