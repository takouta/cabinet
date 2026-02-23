@extends('layouts.dashboard')

@section('title', 'Details cabinet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ $cabinet->nom }}</h1>
                <p class="text-sm text-gray-500">{{ $cabinet->adresse }}</p>
            </div>
            <div class="text-right space-y-1">
                <p class="text-sm text-gray-600">{{ $cabinet->telephone }}</p>
                <p class="text-sm text-gray-600">{{ $cabinet->email ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('super_admin.cabinets.index') }}" class="px-4 py-2 border rounded-lg">Retour</a>
        <a href="{{ route('super_admin.cabinets.edit', $cabinet->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Modifier</a>
    </div>
</div>
@endsection
