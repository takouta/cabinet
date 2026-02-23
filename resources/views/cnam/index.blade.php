@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 font-weight-bold text-primary mb-0">Bordereaux de Soins (BS CNAM)</h1>
            <p class="text-muted">Gestion des transmissions et remboursements CNAM</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('cnam.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-plus mr-2"></i> Nouveau Bordereau
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 border-0">Numéro BS</th>
                        <th class="border-0">Date</th>
                        <th class="border-0">Montant Total</th>
                        <th class="border-0">Statut</th>
                        <th class="border-0 text-right px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bordereaux as $bordereau)
                    <tr>
                        <td class="px-4 font-weight-bold">{{ $bordereau->numero_bs }}</td>
                        <td>{{ $bordereau->date_bordereau }}</td>
                        <td><span class="badge badge-info px-3 py-2" style="font-size: 0.9rem;">{{ number_format($bordereau->montant_total, 3, ',', ' ') }} DT</span></td>
                        <td>
                            @php
                                $statusClass = [
                                    'brouillon' => 'secondary',
                                    'transmis' => 'warning',
                                    'valide' => 'success',
                                    'rejete' => 'danger',
                                    'paye' => 'success shadow-sm'
                                ][$bordereau->statut] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $statusClass }} px-3 py-2 text-capitalize">
                                {{ $bordereau->statut }}
                            </span>
                        </td>
                        <td class="text-right px-4">
                            @if($bordereau->statut === 'brouillon')
                                <form action="{{ route('cnam.transmettre', $bordereau->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-paper-plane mr-1"></i> Transmettre
                                    </button>
                                </form>
                            @endif
                            <button class="btn btn-sm btn-link text-muted">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <div class="mb-3"><i class="fas fa-file-invoice fa-3x opacity-25"></i></div>
                            Aucun bordereau trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bordereaux->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $bordereaux->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .bg-primary-light { background-color: #e7f1ff; }
    .text-primary { color: #0d6efd !important; }
    .btn-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none; }
    .table thead th { text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #6c757d; font-weight: 700; }
    .table td { vertical-align: middle; }
    .card { border: none; }
</style>
@endsection
