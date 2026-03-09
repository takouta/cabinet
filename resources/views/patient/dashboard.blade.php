@extends('layouts.dashboard')

@section('title', 'Mon Espace Patient')
@section('page-title', 'Mon Espace Patient')
@section('page-subtitle', 'Consultez vos rendez-vous et votre historique')

@section('content')
<style>
    .stat-cards { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); 
        gap:1.25rem; 
        margin-bottom:1.75rem; 
    }
    .stat-card {
        background: white; 
        border-radius: 14px; 
        padding: 1.3rem 1.5rem;
        display: flex; 
        align-items: center; 
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        border-left: 4px solid #0288d1; 
        transition: transform 0.25s;
    }
    .stat-card:hover { 
        transform: translateY(-3px); 
    }
    .stat-card.green { 
        border-left-color: #43a047; 
    }
    .stat-card.orange { 
        border-left-color: #fb8c00; 
    }
    .stat-icon {
        width: 46px; 
        height: 46px; 
        border-radius: 12px;
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 1.2rem; 
        background: rgba(2,136,209,0.1); 
        color: #0288d1; 
        flex-shrink:0;
    }
    .stat-card.green .stat-icon { 
        background: rgba(67,160,71,0.1); 
        color: #43a047; 
    }
    .stat-card.orange .stat-icon { 
        background: rgba(251,140,0,0.1); 
        color: #fb8c00; 
    }
    .stat-val { 
        font-size: 1.7rem; 
        font-weight: 700; 
        color: #1a237e; 
        line-height: 1.2;
    }
    .stat-lbl { 
        font-size: 0.8rem; 
        color: #78909c; 
    }

    .section {
        background: white; 
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        overflow: hidden; 
        margin-bottom: 1.5rem;
    }
    .section-header {
        padding: 1rem 1.5rem; 
        border-bottom: 1px solid #f0f4f8;
        display: flex; 
        align-items: center; 
        gap: 0.5rem;
    }
    .section-header h3 { 
        font-size: 1rem; 
        font-weight: 700; 
        color: #1a237e; 
        margin: 0;
    }

    .rdv-item {
        padding: 1rem 1.5rem; 
        border-bottom: 1px solid #f0f4f8;
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        transition: background 0.15s;
    }
    .rdv-item:last-child { 
        border-bottom: none; 
    }
    .rdv-item:hover { 
        background: #f8fbff; 
    }
    .rdv-date {
        width: 52px; 
        height: 52px; 
        border-radius: 12px;
        background: linear-gradient(135deg, #0288d1, #1565c0);
        color: white; 
        display: flex; 
        flex-direction: column;
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0;
    }
    .rdv-date .day { 
        font-size: 1.2rem; 
        font-weight: 700; 
        line-height: 1; 
    }
    .rdv-date .month { 
        font-size: 0.65rem; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }
    .rdv-info { 
        flex: 1; 
        margin: 0 1rem; 
    }
    .rdv-info .rdv-time { 
        font-weight: 600; 
        color: #263238; 
        font-size: 0.9rem; 
    }
    .rdv-info .rdv-time i {
        margin-right: 0.3rem;
    }
    .rdv-info .rdv-doctor { 
        font-size: 0.8rem; 
        color: #78909c; 
        margin-top: 0.15rem; 
    }
    .rdv-info .rdv-doctor i {
        margin-right: 0.3rem;
    }

    .badge {
        padding: 0.25rem 0.65rem; 
        border-radius: 20px;
        font-size: 0.78rem; 
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-confirmed { 
        background: #e8f5e9; 
        color: #2e7d32; 
    }
    .badge-pending { 
        background: #fff3e0; 
        color: #e65100; 
    }
    .badge-cancelled {
        background: #ffebee;
        color: #c62828;
    }

    .info-card {
        background: linear-gradient(135deg, #1565c0, #0288d1);
        border-radius: 14px; 
        padding: 1.5rem; 
        color: white; 
        margin-bottom: 1.5rem;
        display: flex; 
        align-items: center; 
        gap: 1.25rem;
    }
    .info-card .info-icon {
        width: 56px; 
        height: 56px; 
        border-radius: 14px;
        background: rgba(255,255,255,0.2); 
        display: flex; 
        align-items: center;
        justify-content: center; 
        font-size: 1.8rem;
    }
    .info-card .info-text h4 { 
        font-size: 1.1rem; 
        font-weight: 700; 
        margin-bottom: 0.2rem; 
    }
    .info-card .info-text p { 
        font-size: 0.85rem; 
        opacity: 0.85; 
        margin: 0;
    }

    .empty-state { 
        padding: 2rem; 
        text-align: center; 
        color: #b0bec5; 
    }
    .empty-state i { 
        font-size: 2.5rem; 
        display: block; 
        margin-bottom: 0.75rem; 
    }

    .alert-box {
        background: #fff8e1; 
        border-left: 4px solid #fb8c00;
        border-radius: 10px; 
        padding: 1rem 1.25rem; 
        margin-bottom: 1.5rem;
        display: flex; 
        align-items: center; 
        gap: 0.75rem; 
        color: #e65100; 
        font-size: 0.9rem;
    }
    .alert-box i {
        font-size: 1.2rem;
    }

    .historique-lien {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.5rem 1rem;
        background: #f0f4f8;
        border-radius: 8px;
        color: #0288d1;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background 0.2s;
    }
    .historique-lien:hover {
        background: #e1e9f2;
    }
</style>

@if(!$patient ?? true)
    <div class="alert-box">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Votre fiche patient n'est pas encore associée à votre compte utilisateur. Veuillez contacter l'administrateur.</span>
    </div>
@endif

{{-- Carte de bienvenue --}}
<div class="info-card">
    <div class="info-icon"><i class="fas fa-user-circle"></i></div>
    <div class="info-text">
        <h4>Bonjour, {{ Auth::user()->prenom ?? Auth::user()->name }} 👋</h4>
        <p>Bienvenue dans votre espace patient. Suivez vos rendez-vous et votre historique médical.</p>
    </div>
</div>

@if($patient)

{{-- Stats --}}
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div>
            <div class="stat-val">{{ $stats['total_rdv'] ?? 0 }}</div>
            <div class="stat-lbl">Total rendez-vous</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div>
            @php $prochainsCount = isset($prochains_rdv) ? count($prochains_rdv) : 0; @endphp
            <div class="stat-val">{{ $prochainsCount }}</div>
            <div class="stat-lbl">À venir</div>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-history"></i></div>
        <div>
            <div class="stat-val">
                @if(isset($stats['dernier_rdv']) && $stats['dernier_rdv'])
                    {{ optional($stats['dernier_rdv']->date_heure)->format('d/m') }}
                @else
                    --
                @endif
            </div>
            <div class="stat-lbl">Dernier RDV</div>
        </div>
    </div>
</div>

{{-- Prochains rendez-vous --}}
<div class="section">
    <div class="section-header">
        <i class="fas fa-calendar-alt" style="color: #0288d1;"></i>
        <h3>Prochains rendez-vous</h3>
        @if(isset($prochains_rdv) && count($prochains_rdv) > 0)
            <span class="badge badge-confirmed" style="margin-left: auto;">{{ count($prochains_rdv) }} RDV</span>
        @endif
    </div>

    @forelse($prochains_rdv ?? [] as $rdv)
        @php
            $dt = $rdv->date_heure ?? null;
            $carbon = $dt ? \Carbon\Carbon::parse($dt) : null;
            $nomMedecin = $rdv->medecin->nom ?? $rdv->medecin->name ?? $rdv->dentiste->nom ?? 'Médecin';
            $prenomMedecin = $rdv->medecin->prenom ?? $rdv->dentiste->prenom ?? '';
        @endphp
        <div class="rdv-item">
            <div class="rdv-date">
                <div class="day">{{ $carbon ? $carbon->format('d') : '--' }}</div>
                <div class="month">{{ $carbon ? $carbon->format('M') : '' }}</div>
            </div>
            <div class="rdv-info">
                <div class="rdv-time">
                    <i class="far fa-clock"></i>
                    {{ $carbon ? $carbon->format('H:i') : '--:--' }}
                </div>
                <div class="rdv-doctor">
                    <i class="fas fa-user-md"></i>
                    Dr. {{ $prenomMedecin }} {{ $nomMedecin }}
                </div>
                @if($rdv->motif)
                    <div class="rdv-doctor" style="margin-top: 0.25rem;">
                        <i class="fas fa-stethoscope"></i>
                        {{ $rdv->motif }}
                    </div>
                @endif
            </div>
            <span class="badge 
                @if($rdv->statut == 'confirme') badge-confirmed
                @elseif($rdv->statut == 'planifie') badge-pending
                @else badge-cancelled @endif">
                {{ ucfirst($rdv->statut ?? 'planifié') }}
            </span>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>Aucun rendez-vous programmé</p>
            <a href="{{ Route::has('patient.rendezvous.create') ? route('patient.rendezvous.create') : '#' }}" class="historique-lien">
                <i class="fas fa-plus-circle"></i> Prendre un rendez-vous
            </a>
        </div>
    @endforelse
</div>

{{-- Dernière consultation / Note rapide --}}
@if(isset($stats['dernier_rdv']) && $stats['dernier_rdv'])
<div class="section">
    <div class="section-header">
        <i class="fas fa-notes-medical" style="color: #43a047;"></i>
        <h3>Dernière consultation</h3>
    </div>
    <div style="padding: 1.5rem;">
        <p style="margin-bottom: 0.5rem; color: #263238;">
            <strong>Date :</strong> {{ optional($stats['dernier_rdv']->date_heure)->format('d/m/Y') }}
        </p>
        @if($stats['dernier_rdv']->consultation && $stats['dernier_rdv']->consultation->diagnostic)
            <p style="margin-bottom: 0.5rem; color: #263238;">
                <strong>Diagnostic :</strong> {{ $stats['dernier_rdv']->consultation->diagnostic }}
            </p>
        @endif
        <a href="{{ Route::has('patient.historique') ? route('patient.historique') : '#' }}" class="historique-lien">
            <i class="fas fa-history"></i> Voir tout l'historique
        </a>
    </div>
</div>
@endif
@endif
@endsection