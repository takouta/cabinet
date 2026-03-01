<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - SmileCare</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f0f4f8;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #1565c0 0%, #0288d1 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: #80d8ff;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #ffffff;
        }

        .btn-logout {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 7px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.25);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .welcome-card {
            background: linear-gradient(135deg, #1565c0 0%, #0288d1 100%);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(2, 136, 209, 0.3);
            padding: 2rem 2.5rem;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .welcome-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }

        .welcome-date {
            text-align: right;
            color: rgba(255,255,255,0.85);
        }

        .welcome-date .date-big {
            font-size: 2rem;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 4px solid #0288d1;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-card.alert {
            border-left-color: #e53935;
        }

        .stat-card.warning {
            border-left-color: #fb8c00;
        }

        .stat-card.success {
            border-left-color: #43a047;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            background: rgba(2, 136, 209, 0.1);
            color: #0288d1;
            flex-shrink: 0;
        }

        .stat-card.alert .stat-icon {
            background: rgba(229, 57, 53, 0.1);
            color: #e53935;
        }

        .stat-card.warning .stat-icon {
            background: rgba(251, 140, 0, 0.1);
            color: #fb8c00;
        }

        .stat-card.success .stat-icon {
            background: rgba(67, 160, 71, 0.1);
            color: #43a047;
        }

        .stat-number {
            font-size: 1.9rem;
            font-weight: 700;
            color: #1a237e;
            line-height: 1;
        }

        .stat-label {
            color: #78909c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            background: linear-gradient(135deg, #0288d1, #1565c0);
            color: white;
            border: none;
            padding: 0.9rem 1rem;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(2, 136, 209, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 136, 209, 0.4);
            color: white;
        }

        .action-btn.green {
            background: linear-gradient(135deg, #43a047, #2e7d32);
            box-shadow: 0 4px 12px rgba(67, 160, 71, 0.3);
        }

        .action-btn.green:hover {
            box-shadow: 0 6px 20px rgba(67, 160, 71, 0.4);
        }

        .action-btn.orange {
            background: linear-gradient(135deg, #fb8c00, #e65100);
            box-shadow: 0 4px 12px rgba(251, 140, 0, 0.3);
        }

        .action-btn.purple {
            background: linear-gradient(135deg, #8e24aa, #6a1b9a);
            box-shadow: 0 4px 12px rgba(142, 36, 170, 0.3);
        }

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .section {
            background: white;
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: #1565c0;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e3f2fd;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .list-item {
            padding: 0.9rem 0.5rem;
            border-bottom: 1px solid #f0f4f8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.15s;
            border-radius: 8px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background: #f8fbff;
            padding-left: 0.75rem;
        }

        .badge {
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-alert {
            background: #ffebee;
            color: #c62828;
        }

        .badge-warning {
            background: #fff3e0;
            color: #e65100;
        }

        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .chart-container {
            height: 280px;
            margin-top: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #b0bec5;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        @media (max-width: 992px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            .nav-links a { display: none; }
            .welcome-card { flex-direction: column; gap: 1rem; }
            .welcome-date { text-align: left; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">Smile<span>Care</span></div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}"><i class="fas fa-chart-line me-1"></i> Tableau de Bord</a>
            @if(Route::has('admin.patients.index'))
            <a href="{{ route('admin.patients.index') }}"><i class="fas fa-users me-1"></i> Patients</a>
            @endif
            @if(Route::has('admin.rendezvous.index') || Route::has('patient.rendezvous.index'))
            @php 
                $rdvRoute = Auth::user()->role == 'patient' ? 'patient.rendezvous.index' : 
                          (in_array(Auth::user()->role, ['admin', 'admin_cabinet']) ? 'admin.rendezvous.index' : 
                          (Auth::user()->role == 'medecin' ? 'medecin.rendezvous.index' : 'secretaire.rendezvous.index'));
            @endphp
            <a href="{{ route($rdvRoute) }}"><i class="fas fa-calendar-check me-1"></i> Rendez-vous</a>
            @endif
            @if(Route::has('consultations.index'))
            <a href="{{ route('consultations.index') }}"><i class="fas fa-stethoscope me-1"></i> Consultations</a>
            @endif
            @if(Route::has('stock.index'))
            <a href="{{ route('stock.index') }}"><i class="fas fa-boxes me-1"></i> Stock</a>
            @endif
            @if(Route::has('fournisseurs.index'))
            <a href="{{ route('fournisseurs.index') }}"><i class="fas fa-truck me-1"></i> Fournisseurs</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
            </form>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <!-- En-tête de bienvenue -->
        <div class="welcome-card">
            <div>
                <div class="welcome-title">
                    <i class="fas fa-tooth" style="color: rgba(255,255,255,0.8); margin-right: 0.5rem;"></i>
                    Bienvenue, {{ Auth::user()->name }}
                </div>
                <div class="welcome-subtitle">Gérez votre cabinet dentaire efficacement</div>
            </div>
            <div class="welcome-date">
                <div class="date-big">{{ now()->format('d') }}</div>
                <div>{{ now()->translatedFormat('F Y') }}</div>
                <div style="font-size: 0.85rem; opacity: 0.8;">{{ now()->translatedFormat('l') }}</div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['totalPatients'] }}</div>
                    <div class="stat-label">Patients total</div>
                </div>
            </div>
            <div class="stat-card {{ $stats['rendezVousAujourdhui'] > 0 ? 'warning' : 'success' }}">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['rendezVousAujourdhui'] }}</div>
                    <div class="stat-label">RDV aujourd'hui</div>
                </div>
            </div>
            <div class="stat-card {{ $stats['alertesStock'] > 0 ? 'alert' : 'success' }}">
                <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['alertesStock'] }}</div>
                    <div class="stat-label">Alertes stock</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-truck"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['totalFournisseurs'] }}</div>
                    <div class="stat-label">Fournisseurs</div>
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['rdvEnAttente'] }}</div>
                    <div class="stat-label">RDV confirmés</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                <div>
                    <div class="stat-number">{{ $stats['patientsNouveauxMois'] }}</div>
                    <div class="stat-label">Nouveaux patients ce mois</div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            @if(Route::has('patients.create'))
            <a href="{{ route('patients.create') }}" class="action-btn">
                <i class="fas fa-user-plus"></i> Nouveau Patient
            </a>
            @endif
            @if(Route::has('admin.rendezvous.create') || Route::has('patient.rendezvous.create'))
            @php 
                $rdvCreateRoute = Auth::user()->role == 'patient' ? 'patient.rendezvous.create' : 
                                (in_array(Auth::user()->role, ['admin', 'admin_cabinet']) ? 'admin.rendezvous.create' : 
                                (Auth::user()->role == 'medecin' ? 'medecin.rendezvous.create' : 'secretaire.rendezvous.create'));
            @endphp
            <a href="{{ route($rdvCreateRoute) }}" class="action-btn green">
                <i class="fas fa-calendar-plus"></i> Prendre Rendez-vous
            </a>
            @endif
            @if(Route::has('consultations.create'))
            <a href="{{ route('consultations.create') }}" class="action-btn orange">
                <i class="fas fa-stethoscope"></i> Nouvelle Consultation
            </a>
            @endif
            @if(Route::has('stock.create'))
            <a href="{{ route('stock.create') }}" class="action-btn purple">
                <i class="fas fa-box-open"></i> Ajouter Stock
            </a>
            @endif
        </div>

        <div class="grid-container">
            <!-- Colonne gauche -->
            <div>
                <!-- Graphique des patients -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Evolution des patients ({{ now()->year }})
                    </h2>
                    <div class="chart-container">
                        <canvas id="patientsChart"></canvas>
                    </div>
                </div>

                <!-- Rendez-vous aujourd'hui -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-day"></i>
                        Rendez-vous aujourd'hui
                    </h2>
                    @if($rdvAujourdhuiList->count() > 0)
                        @foreach($rdvAujourdhuiList as $rdv)
                            <div class="list-item">
                                <div>
                                    <strong>{{ $rdv->patient->nom ?? 'Patient inconnu' }} {{ $rdv->patient->prenom ?? '' }}</strong>
                                    <br>
                                    <small style="color: #78909c;">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') }} &bull; {{ $rdv->type }}</small>
                                </div>
                                <span class="badge badge-success">Confirmé</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>Aucun rendez-vous aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Colonne droite -->
            <div>
                <!-- Alertes stock -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-exclamation-triangle" style="color: #e53935;"></i>
                        Alertes stock
                    </h2>
                    @if($stockAlerteList->count() > 0)
                        @foreach($stockAlerteList as $stock)
                            <div class="list-item">
                                <div>
                                    <strong>{{ $stock->nom }}</strong>
                                    <br>
                                    <small style="color: #78909c;">Stock: {{ $stock->quantite }} | Seuil: {{ $stock->seuil_alerte }}</small>
                                </div>
                                <span class="badge badge-alert"><i class="fas fa-exclamation-circle"></i> Alerte</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-check-circle" style="color: #43a047;"></i>
                            <p>Aucune alerte de stock</p>
                        </div>
                    @endif
                </div>

                <!-- Rendez-vous à venir -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-clock"></i>
                        Rendez-vous à venir (7 jours)
                    </h2>
                    @if($rdvProchains->count() > 0)
                        @foreach($rdvProchains->take(5) as $rdv)
                            <div class="list-item">
                                <div>
                                    <strong>{{ $rdv->patient->nom ?? 'Patient inconnu' }} {{ $rdv->patient->prenom ?? '' }}</strong>
                                    <br>
                                    <small style="color: #78909c;">{{ \Carbon\Carbon::parse($rdv->date_heure)->format('d/m H:i') }} &bull; {{ $rdv->type }}</small>
                                </div>
                                <span class="badge badge-warning">A venir</span>
                            </div>
                        @endforeach
                        @if($rdvProchains->count() > 5)
                            <div style="text-align: center; margin-top: 0.75rem; color: #90a4ae; font-size: 0.85rem;">
                                ... et {{ $rdvProchains->count() - 5 }} autres
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-check" style="color: #43a047;"></i>
                            <p>Aucun rendez-vous à venir</p>
                        </div>
                    @endif
                </div>

                <!-- Fournisseurs principaux -->
                <div class="section">
                    <h2 class="section-title">
                        <i class="fas fa-building"></i>
                        Fournisseurs principaux
                    </h2>
                    @if($fournisseurs->count() > 0)
                        @foreach($fournisseurs as $fournisseur)
                            <div class="list-item">
                                <div>
                                    <strong>{{ $fournisseur->nom }}</strong>
                                    <br>
                                    <small style="color: #78909c;"><i class="fas fa-phone"></i> {{ $fournisseur->telephone }}</small>
                                </div>
                                <span class="badge badge-success">Actif</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-store-slash"></i>
                            <p>Aucun fournisseur</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('patientsChart').getContext('2d');

            fetch('/dashboard/chart-data')
                .then(response => response.json())
                .then(data => {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Nouveaux patients',
                                data: data.patients,
                                borderColor: '#0288d1',
                                backgroundColor: 'rgba(2, 136, 209, 0.08)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#0288d1',
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true, position: 'top' }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.05)' },
                                    ticks: { stepSize: 1 }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                })
                .catch(() => {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                            datasets: [{
                                label: 'Nouveaux patients',
                                data: [5, 8, 12, 6, 15, 10, 8, 13, 9, 11, 7, 14],
                                borderColor: '#0288d1',
                                backgroundColor: 'rgba(2, 136, 209, 0.08)',
                                borderWidth: 2.5,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                });

            // Auto-refresh toutes les 5 minutes
            setInterval(() => { window.location.reload(); }, 300000);
        });
    </script>
</body>
</html>
