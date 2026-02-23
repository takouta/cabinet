@extends('layouts.app')

@section('title', 'Modifier le Rendez-vous')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit me-2"></i>Modifier le Rendez-vous</h1>
    <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="card-title mb-0">Modifier les informations du rendez-vous</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('rendezvous.update', $rendezvous->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                        <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                            <option value="">SÃ©lectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $rendezvous->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->nom }} {{ $patient->prenom }} - {{ $patient->telephone }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_heure" class="form-label">Date et Heure <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('date_heure') is-invalid @enderror" 
                               id="date_heure" name="date_heure" 
                               value="{{ old('date_heure', \Carbon\Carbon::parse($rendezvous->date_heure)->format('Y-m-d\TH:i')) }}" required>
                        @error('date_heure')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="motif" class="form-label">Motif <span class="text-danger">*</span></label>
                <textarea class="form-control @error('motif') is-invalid @enderror" 
                          id="motif" name="motif" rows="3" required>{{ old('motif', $rendezvous->motif) }}</textarea>
                @error('motif')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                    <option value="en_attente" {{ old('statut', $rendezvous->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmÃ©" {{ old('statut', $rendezvous->statut) == 'confirmÃ©' ? 'selected' : '' }}>ConfirmÃ©</option>
                    <option value="annulÃ©" {{ old('statut', $rendezvous->statut) == 'annulÃ©' ? 'selected' : '' }}>AnnulÃ©</option>
                </select>
                @error('statut')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Mettre Ã  jour
                </button>
                <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
