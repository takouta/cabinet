<ul>
    <li class="mb-2">
        <a href="{{ route('super_admin.dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.dashboard') ? 'bg-gray-700' : '' }}">
            Tableau de bord
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.users.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.users.*') ? 'bg-gray-700' : '' }}">
            Utilisateurs
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.cabinets.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.cabinets.*') ? 'bg-gray-700' : '' }}">
            Cabinets
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.statistiques.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.statistiques.*') ? 'bg-gray-700' : '' }}">
            Statistiques
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.audits.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.audits.*') ? 'bg-gray-700' : '' }}">
            Audits
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.configurations.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.configurations.*') ? 'bg-gray-700' : '' }}">
            Configurations
        </a>
    </li>
    <li class="mb-2">
        <a href="{{ route('super_admin.maintenance.index') }}" class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('super_admin.maintenance.*') ? 'bg-gray-700' : '' }}">
            Maintenance
        </a>
    </li>
</ul>
