<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock - SmileCare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0288d1;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #37474f;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #0288d1;
        }
        
        .btn-logout {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e0f7fa;
        }
        
        .card-title {
            color: #01579b;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: #0288d1;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary:hover {
            background: #0277bd;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eceff1;
        }
        
        .table th {
            background: #e3f2fd;
            color: #01579b;
            font-weight: 600;
        }
        
        .table tr:hover {
            background: #fafafa;
        }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .badge-warning {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .badge-danger {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 0.25rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-edit {
            background: #ff9800;
            color: white;
        }
        
        .btn-edit:hover {
            background: #f57c00;
        }
        
        .btn-delete {
            background: #d32f2f;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c62828;
        }
        
        .btn-view {
            background: #0288d1;
            color: white;
        }
        
        .btn-view:hover {
            background: #0277bd;
        }
        
        .alert-success {
            background: #e8f5e9;
            color: #388e3c;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #c8e6c9;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #90a4ae;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">SmileCare</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Tableau de Bord</a>
            <a href="{{ route('stock.index') }}">Stock</a>
            <a href="{{ route('admin.patients.index') }}">Patients</a>
            <a href="{{ route('admin.rendezvous.index') }}">Rendez-vous</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">ðŸ“¦ Gestion du Stock</h1>
                <a href="{{ route('stock.create') }}" class="btn-primary">+ Ajouter du matériel</a>
            </div>

            <!-- Messages de succès -->
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tableau du stock -->
            @if($stocks->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Seuil d'alerte</th>
                            <th>Statut</th>
                            <th>Fournisseur</th>
                            <th>Prix unitaire</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                            <tr>
                                <td>
                                    <strong>{{ $stock->nom }}</strong>
                                    @if($stock->emplacement)
                                        <br><small>{{ $stock->emplacement }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $stock->quantite }} 
                                    @if($stock->unite)
                                        <small>{{ $stock->unite }}</small>
                                    @endif
                                </td>
                                <td>{{ $stock->seuil_alerte }}</td>
                                <td>
                                    @if($stock->quantite == 0)
                                        <span class="badge badge-danger">RUPTURE</span>
                                    @elseif($stock->quantite <= $stock->seuil_alerte)
                                        <span class="badge badge-warning">FAIBLE</span>
                                    @else
                                        <span class="badge badge-success">NORMAL</span>
                                    @endif
                                </td>
                                <td>{{ $stock->fournisseur->nom ?? 'N/A' }}</td>
                                <td>
                                    @if($stock->prix_unitaire)
                                        {{ number_format($stock->prix_unitaire, 2) }} €
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('stock.edit', $stock->id) }}" class="btn-sm btn-edit">âœï¸</a>
                                        <form action="{{ route('stock.destroy', $stock->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce matériel?')">ðŸ—‘ï¸</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div>ðŸ“¦</div>
                    <h3>Aucun matériel en stock</h3>
                    <p>Commencez par ajouter votre premier matériel</p>
                    <a href="{{ route('stock.create') }}" class="btn-primary" style="margin-top: 1rem;">Ajouter du matériel</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Script pour améliorer l'interface
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des tooltips pour les boutons
            const buttons = document.querySelectorAll('.btn-sm');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    const action = this.textContent.includes('âœï¸') ? 'Modifier' : 
                                  this.textContent.includes('ðŸ—‘ï¸') ? 'Supprimer' : 'Voir';
                    this.title = action;
                });
            });

            // Auto-refresh toutes les 5 minutes
            setInterval(() => {
                window.location.reload();
            }, 300000);
        });
    </script>
</body>
</html>
