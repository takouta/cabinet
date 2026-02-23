<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Stock - SmileCare</title>
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
            max-width: 800px;
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
        
        .btn-back {
            background: #546e7a;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .btn-back:hover {
            background: #455a64;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #37474f;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #b0bec5;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: #0288d1;
            outline: none;
            box-shadow: 0 0 0 2px rgba(2, 136, 209, 0.2);
        }
        
        .form-text {
            color: #546e7a;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .btn-primary {
            background: #0288d1;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
            background: #0277bd;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: #ffebee;
            color: #d32f2f;
            border: 1px solid #ffcdd2;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .card {
                padding: 1.5rem;
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
            <a href="{{ route('patients.index') }}">Patients</a>
            <a href="{{ route('rendezvous.index') }}">Rendez-vous</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">DÃ©connexion</button>
            </form>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Modifier le matÃ©riel</h1>
                <a href="{{ route('stock.index') }}" class="btn-back">â† Retour au stock</a>
            </div>

            <!-- Affichage des erreurs -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style-type: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire de modification -->
            <form action="{{ route('stock.update', $stock->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nom" class="form-label">Nom du matÃ©riel *</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="{{ old('nom', $stock->nom) }}" required>
                    <div class="form-text">Nom complet du matÃ©riel ou produit</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantite" class="form-label">QuantitÃ© en stock *</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               value="{{ old('quantite', $stock->quantite) }}" min="0" step="1" required>
                        <div class="form-text">QuantitÃ© actuelle disponible</div>
                    </div>

                    <div class="form-group">
                        <label for="seuil_alerte" class="form-label">Seuil d'alerte *</label>
                        <input type="number" class="form-control" id="seuil_alerte" name="seuil_alerte" 
                               value="{{ old('seuil_alerte', $stock->seuil_alerte) }}" min="0" step="1" required>
                        <div class="form-text">Alerte quand le stock est infÃ©rieur ou Ã©gal Ã  cette valeur</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="unite" class="form-label">UnitÃ© de mesure</label>
                    <input type="text" class="form-control" id="unite" name="unite" 
                           value="{{ old('unite', $stock->unite) }}" placeholder="piÃ¨ces, kg, litres...">
                    <div class="form-text">UnitÃ© de mesure (ex: piÃ¨ces, kg, litres, boÃ®tes)</div>
                </div>

                <div class="form-group">
                    <label for="fournisseur" class="form-label">Fournisseur</label>
                    <input type="text" class="form-control" id="fournisseur" name="fournisseur" 
                           value="{{ old('fournisseur', $stock->fournisseur) }}">
                    <div class="form-text">Nom du fournisseur principal</div>
                </div>

                <div class="form-group">
                    <label for="prix_unitaire" class="form-label">Prix unitaire (â‚¬)</label>
                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" 
                           value="{{ old('prix_unitaire', $stock->prix_unitaire) }}" min="0" step="0.01">
                    <div class="form-text">Prix d'achat unitaire</div>
                </div>

                <div class="form-group">
                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                    <input type="date" class="form-control" id="date_expiration" name="date_expiration" 
                           value="{{ old('date_expiration', $stock->date_expiration ? \Carbon\Carbon::parse($stock->date_expiration)->format('Y-m-d') : '') }}">
                    <div class="form-text">Date de pÃ©remption si applicable</div>
                </div>

                <div class="form-group">
                    <label for="emplacement" class="form-label">Emplacement</label>
                    <input type="text" class="form-control" id="emplacement" name="emplacement" 
                           value="{{ old('emplacement', $stock->emplacement) }}">
                    <div class="form-text">Localisation dans le stock (ex: Armoire A, Ã‰tage 2)</div>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes', $stock->notes) }}</textarea>
                    <div class="form-text">Informations supplÃ©mentaires</div>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">
                        ðŸ’¾ Mettre Ã  jour le stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validation basique
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const quantite = document.getElementById('quantite').value;
                const seuil = document.getElementById('seuil_alerte').value;
                
                if (parseInt(quantite) < 0) {
                    e.preventDefault();
                    alert('La quantitÃ© ne peut pas Ãªtre nÃ©gative');
                    return false;
                }
                
                if (parseInt(seuil) < 0) {
                    e.preventDefault();
                    alert('Le seuil d\'alerte ne peut pas Ãªtre nÃ©gatif');
                    return false;
                }
            });

            // Calcul automatique du statut
            function updateStatut() {
                const quantite = parseInt(document.getElementById('quantite').value) || 0;
                const seuil = parseInt(document.getElementById('seuil_alerte').value) || 0;
                
                const statutElement = document.getElementById('statut-display');
                if (statutElement) {
                    if (quantite === 0) {
                        statutElement.textContent = 'RUPTURE DE STOCK';
                        statutElement.style.color = '#d32f2f';
                    } else if (quantite <= seuil) {
                        statutElement.textContent = 'STOCK FAIBLE';
                        statutElement.style.color = '#ff9800';
                    } else {
                        statutElement.textContent = 'STOCK NORMAL';
                        statutElement.style.color = '#388e3c';
                    }
                }
            }

            // Ã‰couter les changements de quantitÃ© et seuil
            document.getElementById('quantite').addEventListener('input', updateStatut);
            document.getElementById('seuil_alerte').addEventListener('input', updateStatut);
            
            // Initialiser le statut
            updateStatut();
        });
    </script>
</body>
</html>
