@extends('layouts.dashboard')

@section('title', 'Modifier cabinet')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Modifier cabinet</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <p class="font-medium mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('super_admin.cabinets.update', $cabinet->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm text-gray-600">Nom</label>
            <input name="nom" value="{{ old('nom', $cabinet->nom) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm text-gray-600">Adresse</label>
            <input name="adresse" value="{{ old('adresse', $cabinet->adresse) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm text-gray-600">Telephone</label>
            <input type="tel" name="telephone" value="{{ old('telephone', $cabinet->telephone) }}" class="w-full border rounded-lg px-3 py-2" pattern="[0-9]{8,30}" title="Entrez uniquement des chiffres (8 a 30)." required>
        </div>

        <div>
            <label class="text-sm text-gray-600">Email</label>
            <input type="email" name="email" value="{{ old('email', $cabinet->email) }}" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('super_admin.cabinets.show', $cabinet->id) }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
