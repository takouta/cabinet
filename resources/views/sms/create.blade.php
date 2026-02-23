@extends('layouts.app')

@section('title', 'Nouveau SMS')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Envoyer un SMS</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('sms.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_destinataire" class="form-label">NumÃ©ro du destinataire *</label>
                        <input type="text" class="form-control @error('numero_destinataire') is-invalid @enderror" 
                               id="numero_destinataire" name="numero_destinataire" 
                               value="{{ old('numero_destinataire') }}" 
                               placeholder="Ex: 06 12 34 56 78" required>
                        @error('numero_destinataire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de message *</label>
                        <select class="form-control @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">SÃ©lectionnez un type</option>
                            <option value="rappel_rdv" {{ old('type') == 'rappel_rdv' ? 'selected' : '' }}>Rappel rendez-vous</option>
                            <option value="alerte_stock" {{ old('type') == 'alerte_stock' ? 'selected' : '' }}>Alerte stock</option>
                            <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
                            <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message *</label>
                <textarea class="form-control @error('message') is-invalid @enderror" 
                          id="message" name="message" rows="6" 
                          placeholder="Tapez votre message ici..." 
                          maxlength="500" required>{{ old('message') }}</textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <span id="charCount">0</span>/500 caractÃ¨res
                </div>
            </div>

            <!-- ModÃ¨les de messages -->
            <div class="mb-3">
                <label class="form-label">ModÃ¨les de messages</label>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" data-template="Rappel: Votre rendez-vous est prÃ©vu le [date] Ã  [heure]. Merci de confirmer votre prÃ©sence.">
                        Rappel RDV
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" data-template="Bonjour, nous vous rappelons votre consultation dentaire de demain. Cabinet SmileCare.">
                        Rappel Simple
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" data-template="Alerte stock: [produit] en quantitÃ© critique. RÃ©approvisionnement urgent nÃ©cessaire.">
                        Alerte Stock
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm template-btn" data-template="Promotion spÃ©ciale: Bilan dentaire gratuit ce mois-ci. RÃ©servez dÃ¨s maintenant!">
                        Promotion
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('sms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour Ã  la liste
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer le SMS
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Informations importantes -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations importantes</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fas fa-sms me-2 text-primary"></i>Format des numÃ©ros</h6>
                <ul class="small">
                    <li>Format franÃ§ais: 06 12 34 56 78</li>
                    <li>Format international: +33 6 12 34 56 78</li>
                    <li>Pas d'espaces ni de caractÃ¨res spÃ©ciaux</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="fas fa-clock me-2 text-warning"></i>DÃ©lais d'envoi</h6>
                <ul class="small">
                    <li>Messages instantanÃ©s</li>
                    <li>Heures d'ouverture: 8h-20h</li>
                    <li>Statut mis Ã  jour automatiquement</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Compteur de caractÃ¨res
document.getElementById('message').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
    
    if (charCount > 450) {
        document.getElementById('charCount').classList.add('text-warning');
    } else {
        document.getElementById('charCount').classList.remove('text-warning');
    }
});

// ModÃ¨les de messages
document.querySelectorAll('.template-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('message').value = this.getAttribute('data-template');
        document.getElementById('message').dispatchEvent(new Event('input'));
    });
});

// SÃ©lection automatique du type basÃ© sur le modÃ¨le
document.querySelectorAll('.template-btn').forEach(button => {
    button.addEventListener('click', function() {
        const template = this.getAttribute('data-template');
        if (template.includes('Rappel')) {
            document.getElementById('type').value = 'rappel_rdv';
        } else if (template.includes('Alerte')) {
            document.getElementById('type').value = 'alerte_stock';
        } else if (template.includes('Promotion')) {
            document.getElementById('type').value = 'promotion';
        }
    });
});
</script>
@endpush
