@extends('layouts.dashboard')

@section('title', 'Nouveau Bordereau')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nouveau Bordereau de Soins</h1>
            <p class="text-sm text-gray-500">Sélectionnez les actes effectués pour générer le BS.</p>
        </div>
        @php
            $currentPrefix = str_contains(request()->route()->getName(), 'admin') ? 'admin.' : (str_contains(request()->route()->getName(), 'medecin') ? 'medecin.' : '');
        @endphp
        <a href="{{ route($currentPrefix . 'cnam.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>

    <form action="{{ route($currentPrefix . 'cnam.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Actes non facturés à la CNAM</h2>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="checkAll" class="text-xs text-gray-500 font-medium cursor-pointer">Tout sélectionner</label>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-white text-gray-500 uppercase text-xs font-semibold border-b">
                        <tr>
                            <th class="px-6 py-3 w-12"></th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Patient</th>
                            <th class="px-6 py-3">Acte</th>
                            <th class="px-6 py-3 text-right">Part CNAM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($soinsDisponibles as $soin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="soin_ids[]" value="{{ $soin->id }}" 
                                    class="soin-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    data-montant="{{ $soin->part_cnam }}">
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($soin->date_soin)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $soin->patient->nom_complet }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-bold mr-2 uppercase border border-gray-200">{{ $soin->acte_code }}</span>
                                    <span class="text-gray-700">{{ $soin->designation }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-blue-600">
                                {{ number_format($soin->part_cnam, 3, ',', ' ') }} DT
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-check-circle fa-3x text-gray-100 mb-4"></i>
                                    <p class="text-gray-500">Tous les actes sont déjà inclus dans des bordereaux.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            <div class="bg-gray-800 rounded-2xl shadow-lg p-6 w-full md:w-96 text-white transform hover:scale-[1.02] transition-transform">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400 text-sm">Actes sélectionnés</span>
                    <span id="selectedCount" class="bg-blue-600 text-xs font-bold px-2.5 py-1 rounded-full">0</span>
                </div>
                <div class="flex justify-between items-end mb-6">
                    <span class="text-gray-400 text-sm">Total CNAM</span>
                    <div class="text-right">
                        <span id="totalCnam" class="text-2xl font-bold">0,000</span>
                        <span class="text-xs text-gray-400 ml-1">DT</span>
                    </div>
                </div>
                <button type="submit" id="btnSubmit" disabled
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-700 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-file-invoice"></i>
                    Générer le Bordereau
                </button>
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
                    total += parseFloat(cb.dataset.montant || 0);
                }
            });

            countSpan.textContent = count;
            totalSpan.textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
            btnSubmit.disabled = count === 0;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                updateTotal();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });
</script>
@endpush
@endsection
