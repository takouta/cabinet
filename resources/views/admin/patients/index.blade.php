@extends('layouts.dashboard')

@section('title', 'Gestion des Patients')
@section('page-title', 'Patients')
@section('page-subtitle', 'Gérer les patients du cabinet')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">Liste des patients</h3>
        <a href="{{ route('admin.patients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Nouveau patient
        </a>
    </div>
    
    <div class="p-4">
        <p class="text-gray-500">Le contrôleur PatientController est maintenant fonctionnel !</p>
    </div>
</div>
@endsection
