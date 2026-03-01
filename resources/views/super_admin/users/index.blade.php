@extends('layouts.dashboard')

@section('title', 'Utilisateurs')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Utilisateurs</h1>
            <p class="text-sm text-gray-500">Gestion des comptes et des acces.</p>
        </div>
        <a href="{{ route('super_admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            <i class="fas fa-user-plus mr-2"></i>Nouvel utilisateur
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow">
            <p class="text-xs text-gray-500">Total</p>
            <p class="text-xl font-semibold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow">
            <p class="text-xs text-gray-500">Actifs</p>
            <p class="text-xl font-semibold">{{ $stats['actifs'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow">
            <p class="text-xs text-gray-500">Patients</p>
            <p class="text-xl font-semibold">{{ $stats['patients'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow">
            <p class="text-xs text-gray-500">Medecins</p>
            <p class="text-xl font-semibold">{{ $stats['medecins'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-4">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" class="border rounded-lg px-3 py-2" placeholder="Rechercher...">
            <select name="role" class="border rounded-lg px-3 py-2">
                <option value="">Tous les roles</option>
                @foreach(['admin_cabinet','admin'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                @endforeach
            </select>
            <select name="statut" class="border rounded-lg px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="actif" @selected(request('statut') === 'actif')>Actif</option>
                <option value="inactif" @selected(request('statut') === 'inactif')>Inactif</option>
            </select>
            <button class="bg-gray-800 text-white rounded-lg px-4 py-2">Filtrer</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Utilisateur</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Statut</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-800">
                                    {{ trim(($user->prenom ?? '').' '.($user->nom ?? '')) ?: ($user->name ?? 'Utilisateur') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $user->telephone ?? 'Non renseigne' }}</div>
                            </td>
                            <td class="px-6 py-3">{{ $user->email }}</td>
                            <td class="px-6 py-3">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <a href="{{ route('super_admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('super_admin.users.edit', $user->id) }}" class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('super_admin.users.destroy', $user->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Supprimer cet utilisateur ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <button onclick="toggleUser({{ $user->id }})" class="text-{{ $user->actif ? 'orange' : 'green' }}-600 hover:text-{{ $user->actif ? 'orange' : 'green' }}-800" title="{{ $user->actif ? 'Desactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $user->actif ? 'user-slash' : 'user-check' }}"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-gray-500" colspan="5">Aucun utilisateur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
function toggleUser(id) {
    if (confirm('Voulez-vous changer le statut de cet utilisateur ?')) {
        fetch(`/super-admin/users/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur reseau');
        });
    }
}
</script>
@endsection
