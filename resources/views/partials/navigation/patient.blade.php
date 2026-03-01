<li>
    <a href="{{ route('patient.dashboard') }}"
       class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

<li>
    @if(Route::has('patient.rendezvous.index'))
        <a href="{{ route('patient.rendezvous.index') }}"
           class="{{ request()->routeIs('patient.rendezvous.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            Mes Rendez-vous
        </a>
    @endif
</li>
