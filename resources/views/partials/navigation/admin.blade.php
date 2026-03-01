@php
    $patientsCount = \App\Modules\Patient\Models\Patient::count();
    $rdvTodayCount = \App\Modules\RendezVous\Models\RendezVous::whereDate('date_heure', today())->count();
    $stockAlert = \App\Modules\Stock\Models\StockMatierePremiere::whereColumn('quantite', '<=', 'seuil_alerte')->count();
@endphp

<li>
    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-chart-pie w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Tableau de bord</span>
    </a>
</li>

@if(Route::has('admin.patients.index'))
<li>
    <a href="{{ route('admin.patients.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('patients.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-users w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Patients</span>
        <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">{{ $patientsCount }}</span>
    </a>
</li>
@endif

<li x-data="{ openRdv: {{ request()->routeIs('rendezvous.*') ? 'true' : 'false' }} }">
    <button type="button" @click="openRdv = !openRdv" class="flex items-center w-full p-2 text-base text-gray-900 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
        <i class="fas fa-calendar-check w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="flex-1 ml-3 text-left whitespace-nowrap">Rendez-vous</span>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>
    <ul x-show="openRdv" class="py-2 space-y-2">
        @if(Route::has('admin.rendezvous.index'))
            <li>
                <a href="{{ route('admin.rendezvous.index') }}" class="flex items-center p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">Tous les RDV</a>
            </li>
        @endif
        @if(Route::has('admin.rendezvous.index'))
            <li>
                <a href="{{ route('admin.rendezvous.index') }}" class="flex items-center p-2 pl-11 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    Aujourd'hui
                    <span class="inline-flex items-center justify-center px-2 py-1 ml-3 text-xs font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $rdvTodayCount }}</span>
                </a>
            </li>
        @endif
    </ul>
</li>

@if(Route::has('stock.index'))
<li>
    <a href="{{ route('stock.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('stock.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-boxes w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Stock</span>
        @if($stockAlert > 0)
            <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">{{ $stockAlert }}</span>
        @endif
    </a>
</li>
@endif

@if(Route::has('fournisseurs.index'))
<li>
    <a href="{{ route('fournisseurs.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('fournisseurs.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-truck w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Fournisseurs</span>
    </a>
</li>
@endif

@if(Route::has('consultations.index'))
<li>
    <a href="{{ route('consultations.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('consultations.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-file-medical w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Consultations</span>
    </a>
</li>
@endif

@if(Route::has('admin.cnam.index'))
<li>
    <a href="{{ route('admin.cnam.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.cnam.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-file-invoice-dollar w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">CNAM (BS)</span>
    </a>
</li>
@endif

@if(Route::has('sms.index'))
<li>
    <a href="{{ route('sms.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('sms.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-comment-dots w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">SMS</span>
    </a>
</li>
@endif

@if(Route::has('admin.users.index'))
<li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
    <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600' : '' }}">
        <i class="fas fa-user-shield w-5 h-5 text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">Gestion utilisateurs</span>
    </a>
</li>
@endif
