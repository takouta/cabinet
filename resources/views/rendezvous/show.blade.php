@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-check me-2"></i>Détails du Rendez-vous</h1>
    <div>
        <a href="{{ route($routePrefix . '.rendezvous.edit', $rendezvous->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        <a href="{{ route($routePrefix . '.rendezvous.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informations du Rendez-vous</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Patient:</th>
                                <td>
                                    <strong>{{ $rendezvous->patient->nom }} {{ $rendezvous->patient->prenom }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $rendezvous->patient->telephone }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $rendezvous->patient->email }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('d/m/Y') }}</strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Heure:</th>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('H:i') }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    @php
                                        $statutColors = [
                                            'confirmé' => 'success',
                                            'annulé' => 'danger',
                                            'en_attente' => 'warning'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statutColors[$rendezvous->statut] ?? 'secondary' }}">
                                        {{ ucfirst($rendezvous->statut) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $rendezvous->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Modifié le:</th>
                                <td>{{ $rendezvous->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <h6>Motif de la consultation:</h6>
                    <div class="alert alert-light border">
                        {{ $rendezvous->motif }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Informations du patient -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-user-injured me-2"></i>Informations Patient</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Nom:</th>
                        <td>{{ $rendezvous->patient->nom }}</td>
                    </tr>
                    <tr>
                        <th>Prénom:</th>
                        <td>{{ $rendezvous->patient->prenom }}</td>
                    </tr>
                    <tr>
                        <th>Date de Naissance:</th>
                        <td>{{ \Carbon\Carbon::parse($rendezvous->patient->date_naissance)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone:</th>
                        <td>{{ $rendezvous->patient->telephone }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $rendezvous->patient->email }}</td>
                    </tr>
                </table>
                <a href="{{ route('patients.show', $rendezvous->patient->id) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-user me-1"></i>Voir le dossier patient
                </a>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($rendezvous->statut == 'en_attente')
                        <form action="{{ route($routePrefix . '.rendezvous.update', $rendezvous->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="confirmé">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Confirmer RDV
                            </button>
                        </form>
                    @endif

                    @if($rendezvous->statut != 'annulé')
                        <form action="{{ route($routePrefix . '.rendezvous.update', $rendezvous->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="statut" value="annulé">
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Annuler ce rendez-vous ?')">
                                <i class="fas fa-times me-2"></i>Annuler RDV
                            </button>
                        </form>
                    @endif

                    <a href="tel:{{ $rendezvous->patient->telephone }}" class="btn btn-outline-info">
                        <i class="fas fa-phone me-2"></i>Appeler le patient
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
