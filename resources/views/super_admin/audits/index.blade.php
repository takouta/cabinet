@extends('layouts.dashboard')

@section('title', 'Audit')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Audit et activites</h1>
        <p class="text-sm text-gray-500">Suivi des actions et connexions.</p>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Utilisateur</th>
                        <th class="px-6 py-3 text-left">Action</th>
                        <th class="px-6 py-3 text-left">IP</th>
                        <th class="px-6 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $log->user->prenom ?? 'Systeme' }}</td>
                            <td class="px-6 py-3">{{ $log->action ?? 'Action' }}</td>
                            <td class="px-6 py-3">{{ $log->ip_address ?? '-' }}</td>
                            <td class="px-6 py-3">{{ optional($log->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-gray-500" colspan="4">Aucun log.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
