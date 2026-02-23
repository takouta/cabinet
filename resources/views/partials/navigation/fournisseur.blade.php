<li>
    <a href="{{ route('fournisseur.dashboard') }}"
       class="{{ request()->routeIs('fournisseur.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

@if(Route::has('fournisseurs.index'))
<li>
    <a href="{{ route('fournisseurs.index') }}"
       class="{{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
        <i class="fas fa-boxes"></i>
        Mes Produits
    </a>
</li>
@endif
