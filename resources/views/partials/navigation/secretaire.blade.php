<li>
    <a href="{{ route('secretaire.dashboard') }}"
       class="{{ request()->routeIs('secretaire.dashboard') ? 'active' : '' }}">
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
        Patients
    </a>
</li>
@endif

@if(Route::has('sms.index'))
<li>
    <a href="{{ route('sms.index') }}"
       class="{{ request()->routeIs('sms.*') ? 'active' : '' }}">
        <i class="fas fa-comment-dots"></i>
        SMS Rappels
    </a>
</li>
@endif
