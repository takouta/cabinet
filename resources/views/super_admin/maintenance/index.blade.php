@extends('layouts.dashboard')

@section('title', 'Maintenance')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Maintenance</h1>
    <p class="text-sm text-gray-500 mb-4">
        Cette page centralise les actions de maintenance (cache, logs, sauvegardes).
    </p>
    <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center text-gray-500">
        Ajoutez ici vos actions de maintenance selon vos besoins.
    </div>
</div>
@endsection
