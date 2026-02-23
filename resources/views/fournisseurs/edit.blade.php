@extends('layouts.app')

@section('title', 'Modifier Fournisseur')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier le Fournisseur</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du fournisseur *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom', $fournisseur->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $fournisseur->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="telephone" class="form-label">TÃ©lÃ©phone *</label>
                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                               id="telephone" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" required>
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="specialite" class="form-label">SpÃ©cialitÃ© *</label>
                        <select class="form-control @error('specialite') is-invalid @enderror" 
                                id="specialite" name="specialite" required>
                            <option value="">SÃ©lectionnez une spÃ©cialitÃ©</option>
                            <option value="MÃ©dicaments" {{ old('specialite', $fournisseur->specialite) == 'MÃ©dicaments' ? 'selected' : '' }}>MÃ©dicaments</option>
                            <option value="Consommables" {{ old('specialite', $fournisseur->specialite) == 'Consommables' ? 'selected' : '' }}>Consommables</option>
                            <option value="Ã‰quipements" {{ old('specialite', $fournisseur->specialite) == 'Ã‰quipements' ? 'selected' : '' }}>Ã‰quipements</option>
                            <option value="Protection" {{ old('specialite', $fournisseur->specialite) == 'Protection' ? 'selected' : '' }}>Protection</option>
                            <option value="MatÃ©riaux" {{ old('specialite', $fournisseur->specialite) == 'MatÃ©riaux' ? 'selected' : '' }}>MatÃ©riaux</option>
                            <option value="Instruments" {{ old('specialite', $fournisseur->specialite) == 'Instruments' ? 'selected' : '' }}>Instruments</option>
                        </select>
                        @error('specialite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse complÃ¨te *</label>
                <textarea class="form-control @error('adresse') is-invalid @enderror" 
                          id="adresse" name="adresse" rows="3" required>{{ old('adresse', $fournisseur->adresse) }}</textarea>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux dÃ©tails
                </a>
                <div>
                    <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mettre Ã  jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Section Produits AssociÃ©s -->
<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Produits AssociÃ©s</h5>
    </div>
    <div class="card-body">
        @if($fournisseur->stocks->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>CatÃ©gorie</th>
                            <th>QuantitÃ©</th>
                            <th>Seuil Alerte</th>
                            <th>Prix Unitaire</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fournisseur->stocks as $stock)
                        <tr>
                            <td>
                                <a href="{{ route('stock.show', $stock) }}" class="text-decoration-none">
                                    {{ $stock->nom }}
                                </a>
                            </td>
                            <td>{{ $stock->categorie }}</td>
                            <td>{{ $stock->quantite }} {{ $stock->unite_mesure }}</td>
                            <td>{{ $stock->seuil_alerte }} {{ $stock->unite_mesure }}</td>
                            <td>{{ number_format($stock->prix_unitaire, 2, ',', ' ') }} â‚¬</td>
                            <td>
                                @if($stock->quantite <= $stock->seuil_alerte)
                                    <span class="badge bg-danger">Stock Bas</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-3">
                <i class="fas fa-box-open fa-2x mb-2"></i>
                <p>Aucun produit associÃ© Ã  ce fournisseur</p>
            </div>
        @endif
    </div>
</div>
@endsection
