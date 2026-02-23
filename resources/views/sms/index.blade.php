@extends('layouts.app')

@section('title', 'Gestion des SMS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-sms me-2"></i>Gestion des SMS</h1>
    <a href="{{ route('sms.create') }}" class="btn btn-primary">
        <i class="fas fa-paper-plane me-2"></i>Nouveau SMS
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-envelope me-2"></i>Envoi de SMS GroupÃ©s</h5>
            </div>
            <div class="card-body">
                <!-- Rappels RDV -->
                <div class="mb-4">
                    <h6><i class="fas fa-calendar-check me-2 text-success"></i>Rappels Rendez-vous</h6>
                    <form action="{{ route('sms.send-rappel-rdv') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Patients</label>
                                <select name="patient_ids[]" class="form-select" multiple required>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->nom }} {{ $patient->prenom }} - {{ $patient->telephone }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Maintenez Ctrl pour sÃ©lectionner plusieurs patients</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="Message de rappel..." required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer les rappels
                        </button>
                    </form>
                </div>

                <hr>

                <!-- Alertes Stock -->
                <div>
                    <h6><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Alertes Stock</h6>
                    <form action="{{ route('sms.send-alerte-stock') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Fournisseurs</label>
                                <select name="fournisseur_ids[]" class="form-select" multiple required>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">
                                            {{ $fournisseur->nom }} - {{ $fournisseur->telephone }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Maintenez Ctrl pour sÃ©lectionner plusieurs fournisseurs</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="Message d'alerte stock..." required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning mt-3">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer les alertes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistiques SMS -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3>{{ $sms->count() }}</h3>
                    <p class="text-muted">SMS envoyÃ©s</p>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success">{{ $sms->where('statut', 'envoyÃ©')->count() }}</h5>
                        <small class="text-muted">EnvoyÃ©s</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning">{{ $sms->where('statut', 'en_attente')->count() }}</h5>
                        <small class="text-muted">En attente</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Types de SMS -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-tags me-2"></i>RÃ©partition par Type</h5>
            </div>
            <div class="card-body">
                @php
                    $types = $sms->groupBy('type');
                @endphp
                @foreach($types as $type => $messages)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                        <span class="badge bg-primary">{{ $messages->count() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Liste des SMS -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Historique des SMS</h5>
    </div>
    <div class="card-body">
        @if($sms->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Destinataire</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sms as $message)
                        <tr>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $message->numero_destinataire }}</td>
                            <td>
                                <span title="{{ $message->message }}">
                                    {{ Str::limit($message->message, 50) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $message->type)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statutColors = [
                                        'envoyÃ©' => 'success',
                                        'en_attente' => 'warning',
                                        'Ã©chouÃ©' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statutColors[$message->statut] ?? 'secondary' }}">
                                    {{ ucfirst($message->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('sms.show', $message->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('sms.destroy', $message->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('ÃŠtes-vous sÃ»r ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucun SMS trouvÃ©.
            </div>
        @endif
    </div>
</div>
@endsection
