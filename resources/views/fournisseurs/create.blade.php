@extends('layouts.app')

@section('title', 'Nouveau Fournisseur')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Nouveau Fournisseur</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('fournisseurs.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du fournisseur *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
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
                               id="telephone" name="telephone" value="{{ old('telephone') }}" required>
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
                            <option value="MÃ©dicaments" {{ old('specialite') == 'MÃ©dicaments' ? 'selected' : '' }}>MÃ©dicaments</option>
                            <option value="Consommables" {{ old('specialite') == 'Consommables' ? 'selected' : '' }}>Consommables</option>
                            <option value="Ã‰quipements" {{ old('specialite') == 'Ã‰quipements' ? 'selected' : '' }}>Ã‰quipements</option>
                            <option value="Protection" {{ old('specialite') == 'Protection' ? 'selected' : '' }}>Protection</option>
                            <option value="MatÃ©riaux" {{ old('specialite') == 'MatÃ©riaux' ? 'selected' : '' }}>MatÃ©riaux</option>
                            <option value="Instruments" {{ old('specialite') == 'Instruments' ? 'selected' : '' }}>Instruments</option>
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
                          id="adresse" name="adresse" rows="3" required>{{ old('adresse') }}</textarea>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour Ã  la liste
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>CrÃ©er le Fournisseur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
