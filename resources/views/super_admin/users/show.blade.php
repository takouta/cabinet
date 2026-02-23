@extends('layouts.dashboard')

@section('title', 'Details utilisateur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ trim(($user->prenom ?? '').' '.($user->nom ?? '')) ?: ($user->name ?? 'Utilisateur') }}
                </h1>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <span class="px-3 py-1 text-xs rounded-full {{ $user->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $user->actif ? 'Actif' : 'Inactif' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div>
                <p class="text-xs text-gray-500">Role</p>
                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Telephone</p>
                <p class="font-medium">{{ $user->telephone ?? 'Non renseigne' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Adresse</p>
                <p class="font-medium">{{ $user->adresse ?? 'Non renseignee' }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 border rounded-lg">Retour</a>
        <a href="{{ route('super_admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Modifier</a>
    </div>
</div>
@endsection
