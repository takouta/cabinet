@extends('layouts.dashboard')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Modifier utilisateur</h1>

    <form method="POST" action="{{ route('super_admin.users.update', $user->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600">Prenom</label>
                <input name="prenom" value="{{ old('prenom', $user->prenom) }}" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="text-sm text-gray-600">Nom</label>
                <input name="nom" value="{{ old('nom', $user->nom) }}" class="w-full border rounded-lg px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-600">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="text-sm text-gray-600">Role</label>
            <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600">Telephone</label>
                <input name="telephone" value="{{ old('telephone', $user->telephone) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Adresse</label>
                <input name="adresse" value="{{ old('adresse', $user->adresse) }}" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600">Nouveau mot de passe</label>
                <input type="password" name="password" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Confirmation</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('super_admin.users.show', $user->id) }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
