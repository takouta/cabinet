<li>
    <a href="{{ route('fournisseur.dashboard') }}"
       class="{{ request()->routeIs('fournisseur.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        Tableau de bord
    </a>
</li>

<li>
    <a href="{{ route('fournisseur.factures.index') }}"
       class="{{ request()->routeIs('fournisseur.factures.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        Mes Factures
    </a>
</li>
