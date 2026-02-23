@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
@php
    $user = auth()->user();
    $role = $user->role ?? null;
    $quickLinks = [
        [
            'label' => 'Tableau de bord',
            'icon' => 'fa-chart-line',
            'url' => route('dashboard'),
            'roles' => [],
            'desc' => 'Vue globale et acces rapide aux modules.',
        ],
        [
            'label' => 'Patients',
            'icon' => 'fa-user-injured',
            'url' => url('/patients'),
            'roles' => ['admin_cabinet', 'admin', 'secretaire', 'medecin', 'dentiste'],
            'desc' => 'Fiches patients et suivi medical.',
        ],
        [
            'label' => 'Rendez-vous',
            'icon' => 'fa-calendar-check',
            'url' => url('/rendezvous'),
            'roles' => ['admin_cabinet', 'admin', 'secretaire', 'medecin', 'dentiste'],
            'desc' => 'Planning, disponibilites, confirmations.',
        ],
        [
            'label' => 'Stock',
            'icon' => 'fa-boxes',
            'url' => url('/stock'),
            'roles' => ['admin_cabinet', 'admin', 'super_admin'],
            'desc' => 'Materiel, alertes et seuils.',
        ],
        [
            'label' => 'SMS',
            'icon' => 'fa-sms',
            'url' => url('/sms'),
            'roles' => ['admin_cabinet', 'admin', 'super_admin'],
            'desc' => 'Rappels automatiques et messages.',
        ],
    ];
@endphp

<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 p-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-primary-subtle text-primary mb-3">
                            <i class="fas fa-tooth me-1"></i>
                            Accueil
                        </span>
                        <h1 class="fw-bold mb-2">
                            Bonjour {{ $user->name ?? 'Utilisateur' }}
                        </h1>
                        <p class="text-muted mb-0">
                            Retrouvez vos acces rapides, l'etat du cabinet et les prochaines actions.
                        </p>
                    </div>
                    <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            Aller au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-4">
                @foreach ($quickLinks as $link)
                    @if (empty($link['roles']) || in_array($role, $link['roles']))
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                                        <i class="fas {{ $link['icon'] }}"></i>
                                    </div>
                                    <h5 class="mb-0 fw-semibold">{{ $link['label'] }}</h5>
                                </div>
                                <p class="text-muted small mb-3">{{ $link['desc'] }}</p>
                                <a href="{{ $link['url'] }}" class="btn btn-outline-primary w-100">
                                    Ouvrir
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <div class="card p-4">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Bonnes pratiques du jour
                        </h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2">Verifier les confirmations des rendez-vous du lendemain.</li>
                            <li class="mb-2">Reviser le stock des consommables critiques.</li>
                            <li>Mettre a jour les notes des consultations recentes.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="fw-semibold mb-3">
                            <i class="fas fa-user-md text-primary me-2"></i>
                            Votre role
                        </h5>
                        <div class="bg-light rounded-3 p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Profil actif</span>
                                <span class="fw-semibold text-dark">
                                    {{ $role ? ucfirst(str_replace('_', ' ', $role)) : 'Utilisateur' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Acces</span>
                                <span class="fw-semibold text-dark">Modules adaptes a votre fonction</span>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            Besoin d'un acces supplementaire ? Contactez l'administrateur du cabinet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
