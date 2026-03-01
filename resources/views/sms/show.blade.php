@extends('layouts.app')

@section('title', 'Détails sms')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-sms me-2"></i>Détails du Message</h4>
            </div>
            <div class="card-body">
                <!-- Carte du message -->
                <div class="message-card border rounded p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <small class="text-muted">À:</small>
                            <h6 class="mb-1">{{ $sms->numero_destinataire ?? 'Non défini' }}</h6>
                        </div>
                        <div class="text-end">
                            @php
                                $statutColors = [
                                    'envoyé' => 'success',
                                    'en_attente' => 'warning',
                                    'échoué' => 'danger',
                                    '' => 'secondary'
                                ];
                                $typeColors = [
                                    'rappel_rdv' => 'success',
                                    'alerte_stock' => 'warning', 
                                    'promotion' => 'info',
                                    'autre' => 'secondary',
                                    '' => 'secondary'
                                ];
                                $type = $sms->type ?? 'autre';
                                $statut = $sms->statut ?? 'en_attente';
                                
                                // Gestion des dates
                                $createdAt = $sms->created_at ? \Carbon\Carbon::parse($sms->created_at) : null;
                                $envoyeA = $sms->envoye_a ? \Carbon\Carbon::parse($sms->envoye_a) : null;
                            @endphp
                            <span class="badge bg-{{ $typeColors[$type] ?? 'secondary' }} mb-1">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </span>
                            <br>
                            <span class="badge bg-{{ $statutColors[$statut] ?? 'warning' }}">
                                {{ ucfirst($statut) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="message-content bg-light p-3 rounded">
                        <p class="mb-0">{{ $sms->message ?? 'Aucun message' }}</p>
                    </div>
                    
                    <div class="message-meta mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            @if($createdAt)
                                Créé le {{ $createdAt->format('d/m/Y à H:i') }}
                            @else
                                Date de création non disponible
                            @endif
                            
                            @if($envoyeA)
                                <br>
                                <i class="fas fa-paper-plane me-1"></i>
                                Envoyé le {{ $envoyeA->format('d/m/Y à H:i') }}
                            @endif
                        </small>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('sms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <div>
                        @if($statut == 'en_attente')
                            <button class="btn btn-warning me-2">
                                <i class="fas fa-sync-alt me-2"></i>Relancer
                            </button>
                        @endif
                        {{-- CORRECTION : Utiliser $sms au lieu de $sm --}}
                        <form action="{{ route('sms.destroy', $sms) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce SMS?')">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Informations techniques -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Techniques</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $sms->id }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>
                            <span class="badge bg-{{ $typeColors[$type] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            <span class="badge bg-{{ $statutColors[$statut] ?? 'warning' }}">
                                {{ ucfirst($statut) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Destinataire:</th>
                        <td>{{ $sms->numero_destinataire ?? 'Non défini' }}</td>
                    </tr>
                    <tr>
                        <th>Caractères:</th>
                        <td>{{ strlen($sms->message ?? '') }}/500</td>
                    </tr>
                    <tr>
                        <th>Créé le:</th>
                        <td>
                            @if($createdAt)
                                {{ $createdAt->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">Non disponible</span>
                            @endif
                        </td>
                    </tr>
                    @if($envoyeA)
                    <tr>
                        <th>Envoyé le:</th>
                        <td>{{ $envoyeA->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('sms.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-paper-plane me-2"></i>Nouveau SMS
                    </a>
                    <button class="btn btn-outline-warning" onclick="copyMessage()">
                        <i class="fas fa-copy me-2"></i>Copier le message
                    </button>
                    @if($sms->numero_destinataire)
                    <a href="tel:{{ $sms->numero_destinataire }}" class="btn btn-outline-info">
                        <i class="fas fa-phone me-2"></i>Appeler le destinataire
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message caché pour copie -->
<textarea id="message" style="position: absolute; left: -9999px;">{{ $sms->message ?? '' }}</textarea>
@endsection

@push('scripts')
<script>
function copyMessage() {
    const messageText = document.getElementById('message');
    messageText.select();
    document.execCommand('copy');
    
    // Feedback visuel
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check me-2"></i>Copié!';
    btn.classList.remove('btn-outline-warning');
    btn.classList.add('btn-success');
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-warning');
    }, 2000);
}
</script>
@endpush
