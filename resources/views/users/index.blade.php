@extends('layouts.dashboard')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Utilisateurs')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Liste des utilisateurs</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Nouvel utilisateur
        </a>
    </div>
    
    <table class="min-w-full">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b text-left">Nom</th>
                <th class="py-2 px-4 border-b text-left">Email</th>
                <th class="py-2 px-4 border-b text-left">Rôle</th>
                <th class="py-2 px-4 border-b text-left">Statut</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="py-2 px-4 border-b">{{ $user->prenom }} {{ $user->nom }}</td>
                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b">{{ $user->role }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 text-xs rounded-full {{ $user->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->actif ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="toggleUser({{ $user->id }})" class="text-{{ $user->actif ? 'orange' : 'green' }}-600 hover:text-{{ $user->actif ? 'orange' : 'green' }}-800 mr-2" title="{{ $user->actif ? 'Desactiver' : 'Activer' }}">
                        <i class="fas fa-{{ $user->actif ? 'user-slash' : 'user-check' }}"></i>
                    </button>
                    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<script>
function toggleUser(id) {
    if (confirm('Voulez-vous changer le statut de cet utilisateur ?')) {
        fetch(`/admin/users/${id}/toggle-status`, {
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

function deleteUser(userId) {
    if (confirm('Etes-vous sur de vouloir supprimer cet utilisateur ? Cette action est irreversible.')) {
        document.getElementById(`delete-user-${userId}`).submit();
    }
}
</script>
@endsection
