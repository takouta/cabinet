@extends('layouts.app')

@section('title', 'Gestion des Fournisseurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-truck me-2"></i>Gestion des Fournisseurs</h1>
    <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouveau Fournisseur
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Liste des Fournisseurs ({{ $fournisseurs->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Spécialité</th>
                        <th>Produits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fournisseurs as $fournisseur)
                    <tr>
                        <td>
                            <strong>{{ $fournisseur->nom }}</strong>
                        </td>
                        <td>{{ $fournisseur->email }}</td>
                        <td>{{ $fournisseur->telephone }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $fournisseur->specialite }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $fournisseur->stocks_count ?? $fournisseur->stocks->count() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun fournisseur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
