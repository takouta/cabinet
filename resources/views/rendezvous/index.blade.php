<!DOCTYPE html>
<html>
<head>
    <title>Rendez-vous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Liste des Rendez-vous</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route($routePrefix . '.rendezvous.create') }}" class="btn btn-primary">Nouveau Rendez-vous</a>
            <a href="/dashboard" class="btn btn-secondary">Retour au dashboard</a>
        </div>

        @if($rendezvous->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date/Heure</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rendezvous as $rdv)
                    <tr>
                        <td>{{ $rdv->patient->nom ?? 'N/A' }} {{ $rdv->patient->prenom ?? '' }}</td>
                        <td>{{ $rdv->date_heure->format('d/m/Y H:i') }}</td>
                        <td>{{ $rdv->motif }}</td>
                        <td>
                            <span class="badge bg-{{ $rdv->statut == 'confirmé' ? 'success' : ($rdv->statut == 'annulé' ? 'danger' : 'warning') }}">
                                {{ $rdv->statut }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route($routePrefix . '.rendezvous.show', $rdv) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route($routePrefix . '.rendezvous.edit', $rdv) }}" class="btn btn-warning btn-sm">Modifier</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $rendezvous->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun rendez-vous trouvé.
            </div>
        @endif
    </div>
</body>
</html>
