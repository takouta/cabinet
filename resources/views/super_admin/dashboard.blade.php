@extends('layouts.dashboard')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $newUsersThisMonth }} ce mois
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 flex justify-between text-sm">
                <span class="text-green-600"><i class="fas fa-circle mr-1 text-xs"></i>{{ $activeUsers }} actifs</span>
                <span class="text-red-600"><i class="fas fa-circle mr-1 text-xs"></i>{{ $inactiveUsers }} inactifs</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPatients }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-user-plus mr-1"></i>{{ $newPatients }} nouveaux
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-injured text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Medecins</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalMedecins }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $medecinsActifs }} actifs</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-md text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Cabinets</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCabinets }}</p>
                    <p class="text-xs text-gray-500 mt-2">Tous les cabinets</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-content-center">
                    <i class="fas fa-clinic-medical text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>Evolution des inscriptions
                </h3>
            </div>
            <div class="h-64">
                <canvas id="evolutionChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-pie-chart text-purple-600 mr-2"></i>Repartition des utilisateurs
            </h3>
            <div class="flex items-center justify-center">
                <canvas id="repartitionChart" class="w-64 h-64"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="text-center">
                    <span class="text-sm text-gray-500">Patients</span>
                    <p class="text-xl font-bold text-blue-600">{{ $totalPatients }}</p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500">Medecins</span>
                    <p class="text-xl font-bold text-green-600">{{ $totalMedecins }}</p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500">Secretaires</span>
                    <p class="text-xl font-bold text-purple-600">{{ $totalSecretaires }}</p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500">Fournisseurs</span>
                    <p class="text-xl font-bold text-yellow-600">{{ $totalFournisseurs }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
            <i class="fas fa-plus-circle text-3xl mb-3"></i>
            <h4 class="text-lg font-semibold mb-2">Nouveau Cabinet</h4>
            <p class="text-sm text-blue-100 mb-4">Ajouter un nouveau cabinet dentaire</p>
            <a href="{{ route('super_admin.cabinets.create') }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                <i class="fas fa-plus mr-2"></i>Creer
            </a>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-lg p-6 text-white">
            <i class="fas fa-user-plus text-3xl mb-3"></i>
            <h4 class="text-lg font-semibold mb-2">Nouvel Utilisateur</h4>
            <p class="text-sm text-green-100 mb-4">Creer un compte pour un medecin, secretaire...</p>
            <a href="{{ route('super_admin.users.create') }}" class="inline-block bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition">
                <i class="fas fa-plus mr-2"></i>Creer
            </a>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
            <i class="fas fa-file-alt text-3xl mb-3"></i>
            <h4 class="text-lg font-semibold mb-2">Rapport Mensuel</h4>
            <p class="text-sm text-purple-100 mb-4">Consulter les rapports d'activite</p>
            <a href="{{ route('super_admin.statistiques.rapports') }}" class="inline-block bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-purple-50 transition">
                <i class="fas fa-download mr-2"></i>Voir
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users text-blue-600 mr-2"></i>Derniers utilisateurs inscrits
            </h3>
            <a href="{{ route('super_admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Voir tous <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? 'U', 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ trim(($user->prenom ?? '') . ' ' . ($user->nom ?? '')) ?: ($user->name ?? 'Utilisateur') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $user->telephone ?? 'Non renseigne' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($user->role == 'super_admin') bg-red-100 text-red-800
                                @elseif($user->role == 'admin_cabinet' || $user->role == 'admin') bg-purple-100 text-purple-800
                                @elseif($user->role == 'medecin' || $user->role == 'dentiste') bg-green-100 text-green-800
                                @elseif($user->role == 'secretaire' || $user->role == 'assistant') bg-yellow-100 text-yellow-800
                                @elseif($user->role == 'patient') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ optional($user->created_at)->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-3">
                                <a href="{{ route('super_admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('super_admin.users.edit', $user->id) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="toggleUserStatus({{ $user->id }})" class="text-{{ $user->actif ? 'red' : 'green' }}-600 hover:text-{{ $user->actif ? 'red' : 'green' }}-800" title="{{ $user->actif ? 'Desactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $user->actif ? 'ban' : 'check-circle' }}"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500" colspan="6">Aucun utilisateur.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history text-gray-600 mr-2"></i>Dernieres activites
            </h3>
            <a href="{{ route('super_admin.audits.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Voir tout l'audit
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentActivities as $activity)
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-{{ $activity->icon ?? 'circle' }} text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ optional($activity->created_at)->diffForHumans() }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ $activity->user->prenom ?? 'Systeme' }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-500">Aucune activite recente.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($evolutionLabels) !!},
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: {!! json_encode($evolutionData) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    const repartitionCtx = document.getElementById('repartitionChart');
    if (repartitionCtx) {
        new Chart(repartitionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Patients', 'Medecins', 'Secretaires', 'Fournisseurs', 'Admins'],
                datasets: [{
                    data: [
                        {{ $totalPatients }},
                        {{ $totalMedecins }},
                        {{ $totalSecretaires }},
                        {{ $totalFournisseurs }},
                        {{ $totalAdmins }}
                    ],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(168, 85, 247)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    function toggleUserStatus(userId) {
        if (confirm('Voulez-vous changer le statut de cet utilisateur ?')) {
            fetch(`/super-admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => location.reload());
        }
    }
</script>
@endpush
