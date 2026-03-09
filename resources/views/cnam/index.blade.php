@extends('layouts.dashboard')

@section('title', 'CNAM (BS)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Bordereaux de Soins</h1>
            <p class="text-sm text-gray-500">Gestion des transmissions et remboursements CNAM.</p>
        </div>
        @php
            $routeName = optional(request()->route())->getName() ?? '';
            $currentPrefix = str_contains($routeName, 'admin') ? 'admin.' : (str_contains($routeName, 'medecin') ? 'medecin.' : '');
        @endphp
        
        @if(auth()->check() && in_array(auth()->user()->role, ['medecin', 'dentiste', 'admin_cabinet']))
        <div class="flex gap-3">
            <a href="{{ route($currentPrefix . 'cnam.daily-pdf') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Bordereau Journalier
            </a>
            <a href="{{ route($currentPrefix . 'cnam.soins.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition-colors">
                <i class="fas fa-file-medical mr-2"></i>Saisir nouveau BS
            </a>
            <a href="{{ route($currentPrefix . 'cnam.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Générer Bordereau
            </a>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold border-b">
                    <tr>
                        <th class="px-6 py-4">Numéro BS</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Montant Total</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bordereaux as $bordereau)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $bordereau->numero_bs }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($bordereau->date_bordereau)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ number_format($bordereau->montant_total, 3, ',', ' ') }} DT
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = [
                                    'brouillon' => 'bg-gray-100 text-gray-800',
                                    'transmis' => 'bg-yellow-100 text-yellow-800',
                                    'valide' => 'bg-green-100 text-green-800',
                                    'rejete' => 'bg-red-100 text-red-800',
                                    'paye' => 'bg-emerald-100 text-emerald-800',
                                ];
                                $style = $statusStyles[$bordereau->statut] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $style }} capitalize">
                                {{ $bordereau->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            @if($bordereau->statut === 'brouillon')
                                <form action="{{ route($currentPrefix . 'cnam.transmettre', $bordereau->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium text-xs transition-colors" title="Transmettre à la CNAM">
                                        <i class="fas fa-paper-plane mr-1"></i> Transmettre
                                    </button>
                                </form>
                            @endif
{{-- 
                            <a href="{{ route($currentPrefix . 'cnam.pdf', $bordereau->id) }}" target="_blank" class="text-gray-400 hover:text-red-600 transition-colors" title="Télécharger PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
--}}
                            <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file-invoice fa-3x text-gray-200 mb-4"></i>
                                <p class="text-gray-500 font-medium">Aucun bordereau trouvé.</p>
                                <p class="text-sm text-gray-400 mt-1">Commencez par générer un nouveau bordereau.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bordereaux->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $bordereaux->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
