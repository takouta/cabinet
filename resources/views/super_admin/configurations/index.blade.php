@extends('layouts.dashboard')

@section('title', 'Configurations')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Configurations</h1>
        <p class="text-sm text-gray-500">Parametres globaux de l'application.</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('super_admin.configurations.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <input name="key" class="border rounded-lg px-3 py-2" placeholder="Cle" required>
            <input name="value" class="border rounded-lg px-3 py-2" placeholder="Valeur">
            <button class="bg-blue-600 text-white rounded-lg px-4 py-2">Enregistrer</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Cle</th>
                        <th class="px-6 py-3 text-left">Valeur</th>
                        <th class="px-6 py-3 text-left">Mise a jour</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($configurations as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $config->key }}</td>
                            <td class="px-6 py-3">{{ $config->value }}</td>
                            <td class="px-6 py-3">{{ optional($config->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-gray-500" colspan="3">Aucune configuration.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $configurations->links() }}
        </div>
    </div>
</div>
@endsection
