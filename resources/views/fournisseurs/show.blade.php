@extends('layouts.app')

@section('title', 'Détails Fournisseur')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Informations Fournisseur</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID:</th>
                        <td>{{ $fournisseur->id }}</td>
                    </tr>
                    <tr>
                        <th>Nom:</th>
                        <td>{{ $fournisseur->nom }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $fournisseur->email }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone:</th>
                        <td>{{ $fournisseur->telephone }}</td>
                    </tr>
                    <tr>
                        <th>Spécialité:</th>
                        <td>
                            <span class="badge bg-primary">{{ $fournisseur->specialite }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Adresse:</th>
                        <td>{{ $fournisseur->adresse }}</td>
                    </tr>
                    <tr>
                        <th>Créé le:</th>
                        <td>{{ $fournisseur->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Modifié le:</th>
                        <td>{{ $fournisseur->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-4">
                    <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur?')">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Produits Fournis
                    <span class="badge bg-light text-dark ms-2">{{ $stocks->count() }}</span>
                </h4>
            </div>
            <div class="card-body">
                @if($stocks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Catégorie</th>
                                    <th>Quantité</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->nom }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $stock->categorie }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $stock->quantite <= $stock->seuil_alerte ? 'danger' : 'success' }}">
                                            {{ $stock->quantite }} {{ $stock->unite_mesure }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($stock->prix_unitaire, 2, ',', ' ') }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Aucun produit associé à ce fournisseur</p>
                        <a href="{{ route('stock.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Ajouter un produit
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $stocks->count() }}</h4>
                        <small class="text-muted">Produits</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">
                            {{ number_format($stocks->sum('prix_unitaire'), 2, ',', ' ') }} €
                        </h4>
                        <small class="text-muted">Valeur totale</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
