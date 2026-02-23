<li>
    <a href="{{ route('patient.dashboard') }}"
       class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

<li>
    <a href="{{ route('rendezvous.index') }}"
       class="{{ request()->routeIs('rendezvous.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i>
        Mes Rendez-vous
    </a>
</li>
