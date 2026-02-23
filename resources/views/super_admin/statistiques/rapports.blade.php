@extends('layouts.dashboard')

@section('title', 'Rapports')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Rapports d'activite</h1>
    <p class="text-sm text-gray-500 mb-4">
        Cette section est prete pour accueillir des exports et tableaux par periode.
    </p>
    <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center text-gray-500">
        Configurez vos rapports ici (mensuel, trimestriel, annuel).
    </div>
</div>
@endsection
