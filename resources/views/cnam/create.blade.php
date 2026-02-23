@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 font-weight-bold text-primary">Nouveau Bordereau de Soins</h1>
            <p class="text-muted">Sélectionnez les actes effectués pour générer le BS</p>
        </div>
    </div>

    <form action="{{ route('cnam.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-list mr-2 text-primary"></i> Actes non facturés à la CNAM</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 border-0" style="width: 50px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Patient</th>
                            <th class="border-0">Acte</th>
                            <th class="border-0 text-right px-4">Part CNAM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soinsDisponibles as $soin)
                        <tr>
                            <td class="px-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="soin_ids[]" value="{{ $soin->id }}" class="custom-control-input soin-checkbox" id="soin-{{ $soin->id }}" data-montant="{{ $soin->part_cnam }}">
                                    <label class="custom-control-label" for="soin-{{ $soin->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $soin->date_soin }}</td>
                            <td class="font-weight-bold">
                                {{ $soin->patient->nom_complet }}
                            </td>
                            <td>
                                <span class="badge badge-light border text-muted px-2 py-1 mr-2">{{ $soin->acte_code }}</span>
                                {{ $soin->designation }}
                            </td>
                            <td class="text-right px-4 font-weight-bold text-primary">
                                {{ number_format($soin->part_cnam, 3, ',', ' ') }} DT
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <div class="mb-3"><i class="fas fa-check-circle fa-3x opacity-25"></i></div>
                                Tous les actes sont déjà inclus dans des bordereaux.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 ml-auto">
                <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Actes sélectionnés :</span>
                            <span id="selectedCount" class="badge badge-light">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Total CNAM :</h5>
                            <h4 class="mb-0 font-weight-bold"><span id="totalCnam">0,000</span> DT</h4>
                        </div>
                        <button type="submit" class="btn btn-block btn-white text-primary font-weight-bold rounded-pill py-2 shadow-sm" id="btnSubmit" disabled>
                            <i class="fas fa-file-invoice mr-2"></i> Générer le Bordereau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.soin-checkbox');
        const countSpan = document.getElementById('selectedCount');
        const totalSpan = document.getElementById('totalCnam');
        const btnSubmit = document.getElementById('btnSubmit');

        function updateTotal() {
            let count = 0;
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    count++;
                    total += parseFloat(cb.dataset.montant);
                }
            });

            countSpan.textContent = count;
            totalSpan.textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
            btnSubmit.disabled = count === 0;
        }

        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateTotal();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });
</script>
@endpush

<style>
    .btn-white { background-color: #ffffff; border: none; }
    .btn-white:hover { background-color: #f8f9fa; }
    .custom-control-input:checked ~ .custom-control-label::before { background-color: #0d6efd; border-color: #0d6efd; }
</style>
@endsection
