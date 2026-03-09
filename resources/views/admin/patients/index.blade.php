@extends('layouts.dashboard')

@section('title', 'Gestion des Patients')
@section('page-title', 'Patients')
@section('page-subtitle', 'Consultez et gérez la base de données des patients')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <form action="{{ route(request()->route()->getName()) }}" method="GET">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                       placeholder="Rechercher un patient (nom, email, téléphone)...">
            </form>
        </div>
        <a href="{{ route(str_contains(request()->route()->getName(), 'admin') ? 'admin.patients.create' : 'patients.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors">
            <i class="fas fa-user-plus mr-2"></i>Nouveau Patient
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Patients Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold border-b">
                    <tr>
                        <th class="px-6 py-4">Identité</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Date de Naissance</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                    {{ strtoupper(substr($patient->user->nom ?? $patient->nom, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-900">{{ $patient->user->nom ?? $patient->nom }} {{ $patient->user->prenom ?? $patient->prenom }}</div>
                                    <div class="text-gray-500 text-xs text-uppercase mt-0.5">ID: #{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900">{{ $patient->user->email ?? $patient->email }}</div>
                            <div class="text-gray-500 italic">{{ $patient->user->telephone ?? $patient->telephone }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($patient->user->actif ?? true)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route(str_contains(request()->route()->getName(), 'admin') ? 'admin.patients.show' : 'patients.show', $patient->id) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors" title="Voir profil">
                                <i class="fas fa-id-card"></i>
                            </a>
                            <a href="{{ route(str_contains(request()->route()->getName(), 'admin') ? 'admin.patients.edit' : 'patients.edit', $patient->id) }}" 
                               class="text-yellow-600 hover:text-yellow-800 transition-colors" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route(str_contains(request()->route()->getName(), 'admin') ? 'admin.patients.destroy' : 'patients.destroy', $patient->id) }}" 
                                  method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-users-slash fa-3x mb-4 opacity-20"></i>
                                <p class="font-medium text-gray-500">Aucun patient trouvé.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
