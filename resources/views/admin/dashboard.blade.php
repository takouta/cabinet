@extends('layouts.master')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de votre cabinet')

@section('content')
<div class="space-y-6">
    {{-- Statistiques --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Patients actifs --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-l-4 border-blue-500">
            <div class="flex items-center">
                <i class="fas fa-users text-3xl text-blue-500"></i>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Patients actifs</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['patients_total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- RDV aujourd'hui --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-l-4 border-green-500">
            <div class="flex items-center">
                <i class="fas fa-calendar-check text-3xl text-green-500"></i>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">RDV aujourd'hui</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['rendez_vous_aujourdhui'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Stock critique --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <i class="fas fa-boxes text-3xl text-yellow-500"></i>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stock critique</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['produits_alerte'] ?? 0 }}</p>
                </div>
            </div>
            @if(Route::has('admin.stock.index'))
                <div class="mt-3">
                    <a href="{{ route('admin.stock.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-right mr-1"></i>Réapprovisionner
                    </a>
                </div>
            @endif
        </div>

        {{-- BS en attente --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border-l-4 border-purple-500">
            <div class="flex items-center">
                <i class="fas fa-paper-plane text-3xl text-purple-500"></i>
                <div class="ml-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">BS en attente</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['bs_en_attente'] ?? 0 }}</p>
                </div>
            </div>
            @if(($stats['bs_en_attente'] ?? 0) > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    <button onclick="transmettreBS()" class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded hover:bg-purple-200 transition-colors">
                        <i class="fas fa-paper-plane mr-1"></i>Transmettre
                    </button>
                    @if(Route::has('admin.cnam.daily-pdf'))
                    <a href="{{ route('admin.cnam.daily-pdf') }}" target="_blank" class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition-colors">
                        <i class="fas fa-file-pdf mr-1"></i>Bordereau PDF
                    </a>
                    @endif
                </div>
            @else
                @if(Route::has('admin.cnam.daily-pdf'))
                <div class="mt-3">
                    <a href="{{ route('admin.cnam.daily-pdf') }}" target="_blank" class="text-xs text-gray-500 hover:text-red-600 transition-colors">
                        <i class="fas fa-file-pdf mr-1"></i>Bordereau PDF du jour
                    </a>
                </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Rendez-vous de la semaine --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>Rendez-vous de la semaine
            </h3>
            <div class="h-64">
                <canvas id="rdvChart"></canvas>
            </div>
        </div>

        {{-- Top dentistes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top dentistes (mois)
            </h3>
            <div class="h-64">
                <canvas id="topDentistsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Rendez-vous du jour --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-clock text-green-600 mr-2"></i>Rendez-vous du jour
                </h3>
                @if(Route::has('admin.rendez-vous.index'))
                    <a href="{{ route('admin.rendez-vous.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                @endif
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($rendez_vous_aujourdhui ?? [] as $rdv)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-tooth text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $rdv->patient->prenom ?? '' }} {{ $rdv->patient->nom ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $rdv->motif ?? 'Consultation' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ optional($rdv->date_heure)->format('H:i') }}
                                </p>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if(($rdv->statut ?? '') === 'confirme') bg-green-100 text-green-800
                                    @elseif(($rdv->statut ?? '') === 'planifie') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($rdv->statut ?? 'planifié') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-check text-4xl mb-3 opacity-50"></i>
                        <p>Aucun rendez-vous aujourd'hui</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Stock critique --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Stock critique
                </h3>
                @if(Route::has('admin.stock.index'))
                    <a href="{{ route('admin.stock.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Gérer le stock <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                @endif
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($produits_alerte ?? [] as $produit)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $produit->nom }}</p>
                                <p class="text-xs text-gray-500">{{ $produit->categorie ?? 'Produit' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-red-600 font-semibold">
                                    {{ $produit->stock_actuel ?? $produit->quantite ?? 0 }} / {{ $produit->seuil_alerte ?? 5 }}
                                </p>
                                <p class="text-xs text-gray-500">stock actuel / seuil</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-500"></i>
                        <p>Stock suffisant pour tous les produits</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $rdvSemaineData = $rdv_semaine ?? [12, 19, 15, 17, 14, 8];
    $topDentistesData = $top_dentistes ?? [];
    $transmettreBsUrl = Route::has('admin.bs.transmettre-tout') ? route('admin.bs.transmettre-tout') : '#';
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des rendez-vous de la semaine
    const rdvChartEl = document.getElementById('rdvChart');
    if (rdvChartEl && typeof Chart !== 'undefined') {
        const ctx = rdvChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                datasets: [{
                    label: 'Rendez-vous',
                    data: @json($rdvSemaineData),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique des top dentistes
    const topDentistsChartEl = document.getElementById('topDentistsChart');
    if (topDentistsChartEl && typeof Chart !== 'undefined') {
        const dentists = @json($topDentistesData);
        
        // Labels par défaut si pas de données
        let dentistLabels = ['Dr. Martin', 'Dr. Dubois', 'Dr. Petit', 'Dr. Bernard', 'Dr. Leroy'];
        let dentistValues = [25, 22, 18, 15, 12];
        
        // Si des données existent, les utiliser
        if (dentists && dentists.length > 0) {
            dentistLabels = dentists.map(d => {
                if (d.dentiste) {
                    return `Dr. ${d.dentiste.prenom || ''} ${d.dentiste.nom || ''}`.trim();
                }
                return d.nom || `Dentiste ${d.dentiste_id || ''}`;
            });
            dentistValues = dentists.map(d => Number(d.total || d.nombre_rdv || 0));
        }
        
        const ctx = topDentistsChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dentistLabels,
                datasets: [{
                    label: 'Nombre de rendez-vous',
                    data: dentistValues,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 5
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});

// Fonction pour transmettre les BS à la CNAM
function transmettreBS() {
    if (confirm('Voulez-vous transmettre tous les bulletins de soins en attente à la CNAM ?')) {
        fetch('{{ $transmettreBsUrl }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Transmission réussie !');
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la transmission');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
        });
    }
}
</script>
@endpush
