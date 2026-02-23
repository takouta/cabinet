@extends('layouts.dashboard')

@section('title', 'Statistiques')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Statistiques globales</h1>
        <p class="text-sm text-gray-500">Vue d'ensemble des principaux indicateurs.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Utilisateurs</p>
            <p class="text-2xl font-semibold">{{ $stats['totalUsers'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Patients</p>
            <p class="text-2xl font-semibold">{{ $stats['totalPatients'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Medecins</p>
            <p class="text-2xl font-semibold">{{ $stats['totalMedecins'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Secretaires</p>
            <p class="text-2xl font-semibold">{{ $stats['totalSecretaires'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Fournisseurs</p>
            <p class="text-2xl font-semibold">{{ $stats['totalFournisseurs'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Cabinets</p>
            <p class="text-2xl font-semibold">{{ $stats['totalCabinets'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Rapports</h2>
        <p class="text-sm text-gray-500 mb-4">Consultez le detail par periode.</p>
        <a href="{{ route('super_admin.statistiques.rapports') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg">
            Voir les rapports
        </a>
    </div>
</div>
@endsection
