<li>
    <a href="{{ route('medecin.dashboard') }}"
       class="{{ request()->routeIs('medecin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

@if(Route::has('rendezvous.index'))
<li>
    <a href="{{ route('rendezvous.index') }}"
       class="{{ request()->routeIs('rendezvous.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i>
        Rendez-vous
    </a>
</li>
@endif

@if(Route::has('patients.index'))
<li>
    <a href="{{ route('patients.index') }}"
       class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        Mes Patients
    </a>
</li>
@endif

@if(Route::has('consultations.index'))
<li>
    <a href="{{ route('consultations.index') }}"
       class="{{ request()->routeIs('consultations.*') ? 'active' : '' }}">
        <i class="fas fa-file-medical"></i>
        Consultations
    </a>
</li>
@endif
