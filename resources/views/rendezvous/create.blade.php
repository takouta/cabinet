@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous</h1>
        <a href="{{ route($routePrefix . '.rendezvous.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Planifier un nouveau rendez-vous</h5>
        </div>
        <div class="card-body">
            <form action="{{ route($routePrefix . '.rendezvous.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">
                                <i class="fas fa-user-injured me-1"></i>Patient <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                <option value="">Sélectionner un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
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
                            <label for="date_heure" class="form-label">
                                <i class="fas fa-clock me-1"></i>Date et Heure <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control @error('date_heure') is-invalid @enderror" 
                                   id="date_heure" name="date_heure" value="{{ old('date_heure') }}" required>
                            @error('date_heure')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="motif" class="form-label">
                        <i class="fas fa-sticky-note me-1"></i>Motif de la consultation <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('motif') is-invalid @enderror" 
                              id="motif" name="motif" rows="4" 
                              placeholder="Décrivez le motif de la consultation..." required>{{ old('motif') }}</textarea>
                    @error('motif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="statut" class="form-label">
                        <i class="fas fa-info-circle me-1"></i>Statut <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>
                            â³ En attente
                        </option>
                        <option value="confirmé" {{ old('statut') == 'confirmé' ? 'selected' : '' }}>
                            âœ… Confirmé
                        </option>
                        <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>
                            âŒ Annulé
                        </option>
                    </select>
                    @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>Créer le rendez-vous
                    </button>
                    <a href="{{ route($routePrefix . '.rendezvous.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Informations utiles -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Sélectionnez le patient concerné</li>
                        <li><i class="fas fa-check text-success me-2"></i>Choisissez une date et heure disponibles</li>
                        <li><i class="fas fa-check text-success me-2"></i>Décrivez clairement le motif</li>
                        <li><i class="fas fa-check text-success me-2"></i>Définissez le statut approprié</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Statuts :</strong></p>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-warning me-2">â³</span>En attente : À confirmer</li>
                        <li><span class="badge bg-success me-2">âœ…</span>Confirmé : Rendez-vous validé</li>
                        <li><span class="badge bg-danger me-2">âŒ</span>Annulé : Rendez-vous annulé</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #2c3e50;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn-primary {
    background: linear-gradient(135deg, #2c7fb8 0%, #7ed0e8 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44, 127, 184, 0.4);
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2c7fb8;
    box-shadow: 0 0 0 0.2rem rgba(44, 127, 184, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mettre la date et heure actuelles par défaut
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    const defaultDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    
    const dateTimeInput = document.getElementById('date_heure');
    if (dateTimeInput && !dateTimeInput.value) {
        dateTimeInput.value = defaultDateTime;
    }

    // Validation en temps réel
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    });
});
</script>
@endsection
