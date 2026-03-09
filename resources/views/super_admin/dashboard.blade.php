@extends('layouts.dashboard')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Tableau de Bord Super Admin')
@section('page-subtitle', 'Vue globale de la plateforme')

@section('content')

{{-- ===== STATS ===== --}}
<div class="sa-grid-4">
    {{-- Utilisateurs --}}
    <div class="sa-stat-card">
        <div class="sa-stat-header">
            <div>
                <div class="sa-stat-lbl">Total Utilisateurs</div>
                <div class="sa-stat-val">{{ $totalUsers }}</div>
                <div class="sa-stat-meta"><i class="fas fa-arrow-up"></i> {{ $newUsersThisMonth }} ce mois</div>
            </div>
            <div class="sa-stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="sa-stat-footer">
            <span style="color:#43a047"><i class="fas fa-circle" style="font-size:0.55rem"></i> {{ $activeUsers }} actifs</span>
            <span style="color:#e53935"><i class="fas fa-circle" style="font-size:0.55rem"></i> {{ $inactiveUsers }} inactifs</span>
        </div>
    </div>

    {{-- Patients --}}
    <div class="sa-stat-card green">
        <div class="sa-stat-header">
            <div>
                <div class="sa-stat-lbl">Total Patients</div>
                <div class="sa-stat-val">{{ $totalPatients }}</div>
                <div class="sa-stat-meta" style="color:#43a047"><i class="fas fa-user-plus"></i> {{ $newPatients }} nouveaux</div>
            </div>
            <div class="sa-stat-icon"><i class="fas fa-user-injured"></i></div>
        </div>
    </div>

    {{-- Médecins --}}
    <div class="sa-stat-card purple">
        <div class="sa-stat-header">
            <div>
                <div class="sa-stat-lbl">Médecins</div>
                <div class="sa-stat-val">{{ $totalMedecins }}</div>
                <div class="sa-stat-meta" style="color:#78909c">{{ $medecinsActifs }} actifs</div>
            </div>
            <div class="sa-stat-icon"><i class="fas fa-user-md"></i></div>
        </div>
    </div>

    {{-- Cabinets --}}
    <div class="sa-stat-card orange">
        <div class="sa-stat-header">
            <div>
                <div class="sa-stat-lbl">Cabinets</div>
                <div class="sa-stat-val">{{ $totalCabinets }}</div>
                <div class="sa-stat-meta" style="color:#78909c">Tous les cabinets</div>
            </div>
            <div class="sa-stat-icon"><i class="fas fa-clinic-medical"></i></div>
        </div>
    </div>
</div>

{{-- ===== CHARTS ===== --}}
<div class="sa-grid-2">
    <div class="sa-card">
        <div class="sa-card-header">
            <h3><i class="fas fa-chart-line" style="color:#0288d1"></i> Évolution des inscriptions</h3>
        </div>
        <div class="sa-card-body">
            <div class="sa-chart-wrap">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-header">
            <h3><i class="fas fa-chart-pie" style="color:#8e24aa"></i> Répartition des utilisateurs</h3>
        </div>
        <div class="sa-card-body">
            <div class="sa-chart-wrap" style="height:200px">
                <canvas id="repartitionChart"></canvas>
            </div>
            <div class="sa-rep-grid" style="margin-top:1rem">
                <div class="sa-rep-item"><div class="rep-lbl">Patients</div><div class="rep-val" style="color:#0288d1">{{ $totalPatients }}</div></div>
                <div class="sa-rep-item"><div class="rep-lbl">Médecins</div><div class="rep-val" style="color:#43a047">{{ $totalMedecins }}</div></div>
                <div class="sa-rep-item"><div class="rep-lbl">Secrétaires</div><div class="rep-val" style="color:#8e24aa">{{ $totalSecretaires }}</div></div>
                <div class="sa-rep-item"><div class="rep-lbl">Fournisseurs</div><div class="rep-val" style="color:#fb8c00">{{ $totalFournisseurs }}</div></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== ACTIONS RAPIDES ===== --}}
<div class="sa-grid-3">
    <div class="sa-action-card blue">
        <i class="fas fa-plus-circle big"></i>
        <h4>Nouveau Cabinet</h4>
        <p>Ajouter un nouveau cabinet dentaire</p>
        <a href="{{ route('super_admin.cabinets.create') }}" class="sa-action-btn">
            <i class="fas fa-plus"></i> Créer
        </a>
    </div>
    <div class="sa-action-card green">
        <i class="fas fa-user-plus big"></i>
        <h4>Nouvel Utilisateur</h4>
        <p>Créer un compte pour un médecin, secrétaire...</p>
        <a href="{{ route('super_admin.users.create') }}" class="sa-action-btn">
            <i class="fas fa-plus"></i> Créer
        </a>
    </div>
    <div class="sa-action-card purple">
        <i class="fas fa-file-alt big"></i>
        <h4>Rapport Mensuel</h4>
        <p>Consulter les rapports d'activité</p>
        <a href="{{ route('super_admin.statistiques.rapports') }}" class="sa-action-btn">
            <i class="fas fa-download"></i> Voir
        </a>
    </div>
</div>

{{-- ===== DERNIERS UTILISATEURS ===== --}}
<div class="sa-card">
    <div class="sa-card-header">
        <h3><i class="fas fa-users" style="color:#0288d1"></i> Derniers utilisateurs inscrits</h3>
        <a href="{{ route('super_admin.users.index') }}" class="sa-link">Voir tous <i class="fas fa-arrow-right"></i></a>
    </div>
    <div style="overflow-x:auto">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td>
                        <div class="sa-user-cell">
                            <div class="sa-avatar">{{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div class="sa-user-name">{{ trim(($user->prenom ?? '') . ' ' . ($user->nom ?? '')) ?: ($user->name ?? 'Utilisateur') }}</div>
                                <div class="sa-user-phone">{{ $user->telephone ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:#546e7a; font-size:0.85rem">{{ $user->email }}</td>
                    <td>
                        @php
                            $roleClass = match($user->role) {
                                'super_admin'            => 'sa-badge-red',
                                'admin_cabinet', 'admin' => 'sa-badge-purple',
                                'medecin', 'dentiste'    => 'sa-badge-green',
                                'secretaire','assistant' => 'sa-badge-yellow',
                                'patient'                => 'sa-badge-blue',
                                default                  => 'sa-badge-gray',
                            };
                            $roleLabel = match($user->role) {
                                'super_admin'   => 'Super Admin',
                                'admin_cabinet' => 'Admin cabinet',
                                'medecin'       => 'Médecin',
                                'dentiste'      => 'Dentiste',
                                'secretaire'    => 'Secrétaire',
                                'patient'       => 'Patient',
                                'fournisseur'   => 'Fournisseur',
                                default         => ucfirst(str_replace('_', ' ', $user->role)),
                            };
                        @endphp
                        <span class="sa-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                    </td>
                    <td>
                        <span class="sa-badge {{ $user->actif ? 'sa-badge-ok' : 'sa-badge-off' }}">
                            {{ $user->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td style="color:#78909c; font-size:0.82rem">{{ optional($user->created_at)->diffForHumans() }}</td>
                    <td>
                        <div class="sa-actions">
                            <a href="{{ route('super_admin.users.show', $user->id) }}" class="sa-link-blue" title="Voir"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('super_admin.users.edit', $user->id) }}" class="sa-link-green" title="Modifier"><i class="fas fa-edit"></i></a>
                            <button onclick="toggleUserStatus({{ $user->id }})" class="{{ $user->actif ? 'sa-link-orange' : 'sa-link-green' }}" title="{{ $user->actif ? 'Désactiver' : 'Activer' }}">
                                <i class="fas fa-{{ $user->actif ? 'user-slash' : 'user-check' }}"></i>
                            </button>
                            <form id="delete-user-{{ $user->id }}" action="{{ route('super_admin.users.destroy', $user->id) }}" method="POST" class="hidden" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                            <button onclick="deleteUser({{ $user->id }})" class="sa-link-red" title="Supprimer"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-text">Aucun utilisateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== DERNIÈRES ACTIVITÉS ===== --}}
<div class="sa-card">
    <div class="sa-card-header">
        <h3><i class="fas fa-history" style="color:#546e7a"></i> Dernières activités</h3>
        <a href="{{ route('super_admin.audits.index') }}" class="sa-link">Voir tout l'audit <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="sa-card-body">
        @forelse($recentActivities as $activity)
        <div class="sa-activity-item">
            <div class="sa-activity-icon">
                <i class="fas fa-{{ $activity->icon ?? 'circle' }}"></i>
            </div>
            <div class="sa-activity-desc">
                {{ $activity->description }}
                <div class="sa-activity-time">{{ optional($activity->created_at)->diffForHumans() }}</div>
            </div>
            <div class="sa-activity-who">{{ $activity->user->prenom ?? 'Système' }}</div>
        </div>
        @empty
        <p class="empty-text">Aucune activité récente.</p>
        @endforelse
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
                    borderColor: '#0288d1',
                    backgroundColor: 'rgba(2,136,209,0.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#0288d1',
                    pointRadius: 4,
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const repartitionCtx = document.getElementById('repartitionChart');
    if (repartitionCtx) {
        new Chart(repartitionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Patients', 'Médecins', 'Secrétaires', 'Fournisseurs', 'Admins'],
                datasets: [{
                    data: [
                        {{ $totalPatients }},
                        {{ $totalMedecins }},
                        {{ $totalSecretaires }},
                        {{ $totalFournisseurs }},
                        {{ $totalAdmins }}
                    ],
                    backgroundColor: ['#0288d1','#43a047','#8e24aa','#fb8c00','#e53935'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } },
                cutout: '60%'
            }
        });
    }

    function toggleUserStatus(userId) {
        if (confirm('Voulez-vous changer le statut de cet utilisateur ?')) {
            fetch(`/super-admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message || 'Une erreur est survenue');
            })
            .catch(() => alert('Erreur réseau'));
        }
    }

    function deleteUser(userId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
            document.getElementById(`delete-user-${userId}`).submit();
        }
    }
</script>
@endpush
