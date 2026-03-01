@extends('layouts.app')

@section('title', 'Détails Produit')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-box me-2"></i>Détails du Produit</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">ID:</th>
                        <td>{{ $stock->id }}</td>
                    </tr>
                    <tr>
                        <th>Nom:</th>
                        <td><strong>{{ $stock->nom }}</strong></td>
                    </tr>
                    @if(isset($stock->categorie) && $stock->categorie)
                    <tr>
                        <th>Catégorie:</th>
                        <td>
                            <span class="badge bg-secondary">{{ $stock->categorie }}</span>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Quantité:</th>
                        <td>
                            <span class="badge bg-{{ $stock->quantite <= $stock->seuil_alerte ? 'danger' : 'success' }} fs-6">
                                {{ $stock->quantite }} {{ $stock->unite_mesure }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Seuil d'alerte:</th>
                        <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                    </tr>
                    <tr>
                        <th>Unité de mesure:</th>
                        <td>{{ $stock->unite_mesure }}</td>
                    </tr>
                    <tr>
                        <th>Prix unitaire:</th>
                        <td>
                            @if($stock->prix_unitaire)
                                {{ number_format($stock->prix_unitaire, 3, ',', ' ') }} DT
                            @else
                                <span class="text-muted">Non défini</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Fournisseur:</th>
                        <td>
                            @if($stock->fournisseur)
                                <a href="{{ route('fournisseurs.show', $stock->fournisseur_id) }}" class="text-decoration-none">
                                    {{ $stock->fournisseur->nom }}
                                </a>
                            @else
                                <span class="text-muted">Aucun fournisseur</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Créé le:</th>
                        <td>{{ $stock->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Modifié le:</th>
                        <td>{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour au stock
                    </a>
                    <a href="{{ route('stock.edit', $stock) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form action="{{ route('stock.destroy', $stock) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Statut du stock -->
        <div class="card mb-4">
            <div class="card-header bg-{{ $stock->quantite <= $stock->seuil_alerte ? 'warning' : 'success' }} text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statut du Stock</h5>
            </div>
            <div class="card-body text-center">
                @if($stock->quantite == 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h4>RUPTURE DE STOCK</h4>
                        <p class="mb-0">Commande urgente nécessaire</p>
                    </div>
                @elseif($stock->quantite <= $stock->seuil_alerte)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h4>STOCK BAS</h4>
                        <p class="mb-0">{{ $stock->quantite }} {{ $stock->unite_mesure }} restant(s)</p>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h4>STOCK NORMAL</h4>
                        <p class="mb-0">{{ $stock->quantite }} {{ $stock->unite_mesure }} disponible(s)</p>
                    </div>
                @endif

                <!-- Barre de progression -->
                <div class="progress mb-3" style="height: 25px;">
                    @php
                        $maxValue = max($stock->seuil_alerte * 2, $stock->quantite, 1);
                        $percentage = min(100, ($stock->quantite / $maxValue) * 100);
                        $bgClass = $stock->quantite == 0 ? 'bg-danger' : ($stock->quantite <= $stock->seuil_alerte ? 'bg-warning' : 'bg-success');
                    @endphp
                    <div class="progress-bar {{ $bgClass }}" role="progressbar" 
                         style="width: {{ $percentage }}%" 
                         aria-valuenow="{{ $percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
                <small class="text-muted">Niveau de stock: {{ $stock->quantite }}/{{ $maxValue }} {{ $stock->unite_mesure }}</small>
            </div>
        </div>

        <!-- Informations fournisseur -->
        @if($stock->fournisseur)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Informations Fournisseur</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Nom:</th>
                        <td>{{ $stock->fournisseur->nom }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>
                            <a href="mailto:{{ $stock->fournisseur->email }}" class="text-decoration-none">
                                {{ $stock->fournisseur->email }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Téléphone:</th>
                        <td>
                            <a href="tel:{{ $stock->fournisseur->telephone }}" class="text-decoration-none">
                                {{ $stock->fournisseur->telephone }}
                            </a>
                        </td>
                    </tr>
                    @if($stock->fournisseur->specialite)
                    <tr>
                        <th>Spécialité:</th>
                        <td>
                            <span class="badge bg-info">{{ $stock->fournisseur->specialite }}</span>
                        </td>
                    </tr>
                    @endif
                    @if($stock->fournisseur->adresse)
                    <tr>
                        <th>Adresse:</th>
                        <td>{{ $stock->fournisseur->adresse }}</td>
                    </tr>
                    @endif
                </table>
                <a href="{{ route('fournisseurs.show', $stock->fournisseur_id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i>Voir le fournisseur
                </a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Fournisseur</h5>
            </div>
            <div class="card-body text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <p class="mb-0">Aucun fournisseur associé à ce produit</p>
                </div>
                <a href="{{ route('stock.edit', $stock) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit me-1"></i>Assigner un fournisseur
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
