<li>
    <a href="{{ route('secretaire.dashboard') }}"
       class="{{ request()->routeIs('secretaire.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

@if(Route::has('secretaire.rendezvous.index'))
<li>
    <a href="{{ route('secretaire.rendezvous.index') }}"
       class="{{ request()->routeIs('secretaire.rendezvous.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i>
        Rendez-vous
    </a>
</li>
@endif

@if(Route::has('secretaire.patients.index'))
<li>
    <a href="{{ route('secretaire.patients.index') }}"
       class="{{ request()->routeIs('secretaire.patients.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        Patients
    </a>
</li>
@endif

@if(Route::has('secretaire.sms.index'))
<li>
    <a href="{{ route('secretaire.sms.index') }}"
       class="{{ request()->routeIs('secretaire.sms.*') ? 'active' : '' }}">
        <i class="fas fa-comment-dots"></i>
        SMS Rappels
    </a>
</li>
@endif
