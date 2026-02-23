@extends('layouts.app')

@section('title', 'DÃ©tails du Patient')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-injured me-2"></i>DÃ©tails du Patient</h1>
    <div>
        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Modifier
        </a>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Informations Personnelles</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nom:</th>
                        <td>{{ $patient->nom }}</td>
                    </tr>
                    <tr>
                        <th>PrÃ©nom:</th>
                        <td>{{ $patient->prenom }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $patient->email }}</td>
                    </tr>
                    <tr>
                        <th>TÃ©lÃ©phone:</th>
                        <td>{{ $patient->telephone }}</td>
                    </tr>
                    <tr>
                        <th>Date de Naissance:</th>
                        <td>{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Adresse:</th>
                        <td>{{ $patient->adresse }}</td>
                    </tr>
                    <tr>
                        <th>AntÃ©cÃ©d
