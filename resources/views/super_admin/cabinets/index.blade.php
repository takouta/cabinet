@extends('layouts.dashboard')

@section('title', 'Cabinets')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Cabinets</h1>
            <p class="text-sm text-gray-500">Liste des cabinets dentaires.</p>
        </div>
        <a href="{{ route('super_admin.cabinets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Nouveau cabinet
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Adresse</th>
                        <th class="px-6 py-3 text-left">Telephone</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($cabinets as $cabinet)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $cabinet->nom }}</td>
                            <td class="px-6 py-3">{{ $cabinet->adresse }}</td>
                            <td class="px-6 py-3">{{ $cabinet->telephone }}</td>
                            <td class="px-6 py-3">{{ $cabinet->email ?: '-' }}</td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <a href="{{ route('super_admin.cabinets.show', $cabinet->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('super_admin.cabinets.edit', $cabinet->id) }}" class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('super_admin.cabinets.destroy', $cabinet->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Supprimer ce cabinet ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-gray-500" colspan="5">Aucun cabinet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $cabinets->links() }}
        </div>
    </div>
</div>
@endsection
