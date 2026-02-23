@extends('layouts.dashboard')

@section('title', 'Espace Secrétaire')
@section('page-title', 'Tableau de bord Secrétaire')
@section('page-subtitle', 'Gérez les rendez-vous et les patients du cabinet')

@section('content')
<style>
    .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap:1.25rem; margin-bottom:1.75rem; }
    .stat-card {
        background: white; border-radius: 14px; padding: 1.3rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        border-left: 4px solid #0288d1; transition: transform 0.25s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card.green { border-left-color: #43a047; }
    .stat-card.orange { border-left-color: #fb8c00; }
    .stat-card.purple { border-left-color: #8e24aa; }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; background: rgba(2,136,209,0.1); color: #0288d1; flex-shrink:0;
    }
    .stat-card.green .stat-icon { background: rgba(67,160,71,0.1); color: #43a047; }
    .stat-card.orange .stat-icon { background: rgba(251,140,0,0.1); color: #fb8c00; }
    .stat-card.purple .stat-icon { background: rgba(142,36,170,0.1); color: #8e24aa; }
    .stat-val { font-size: 1.7rem; font-weight: 700; color: #1a237e; }
    .stat-lbl { font-size: 0.8rem; color: #78909c; }

    .two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    @media (max-width: 768px) { .two-cols { grid-template-columns: 1fr; } }

    .section {
        background: white; border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .section-header {
        padding: 1rem 1.5rem; border-bottom: 1px solid #f0f4f8;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .section-header h3 { font-size: 1rem; font-weight: 700; color: #1a237e; }

    .rdv-item {
        padding: 0.85rem 1.5rem; border-bottom: 1px solid #f0f4f8;
        display: flex; align-items: center; gap: 1rem;
        transition: background 0.15s;
    }
    .rdv-item:last-child { border-bottom: none; }
    .rdv-item:hover { background: #f8fbff; }
    .time-pill {
        background: #fff3e0; color: #e65100;
        border-radius: 8px; padding: 0.3rem 0.6rem;
        font-size: 0.82rem; font-weight: 700; flex-shrink: 0;
    }
    .time-pill.confirmed {
        background: #e8f5e9; color: #2e7d32;
    }
    .item-name { font-weight: 600; color: #263238; font-size: 0.9rem; }
    .item-sub { font-size: 0.78rem; color: #78909c; margin-top: 0.1rem; }

    .patient-row {
        padding: 0.85rem 1.5rem; border-bottom: 1px solid #f0f4f8;
        display: flex; align-items: center; gap: 1rem;
        transition: background 0.15s;
    }
    .patient-row:last-child { border-bottom: none; }
    .patient-row:hover { background: #f8fbff; }
    .patient-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, #0288d1, #26c6da);
        color: white; display: flex; align-items: center;
        justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink:0;
    }

    .empty-state { padding: 2rem; text-align: center; color: #b0bec5; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
</style>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-val">{{ count($agenda_jour ?? []) }}</div>
            <div class="stat-lbl">RDV aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ count($a_confirmer ?? []) }}</div>
            <div class="stat-lbl">À confirmer</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
        <div>
            <div class="stat-val">{{ count($nouveaux_patients ?? []) }}</div>
            <div class="stat-lbl">Nouveaux patients</div>
        </div>
    </div>
</div>

<div class="two-cols">
    {{-- RDV à confirmer --}}
    <div class="section">
        <div class="section-header">
            <i class="fas fa-clock" style="color: #fb8c00;"></i>
            <h3>À confirmer</h3>
        </div>
        @forelse($a_confirmer as $rdv)
            <div class="rdv-item">
                <div class="time-pill">{{ optional($rdv->date_heure)->format('d/m H:i') }}</div>
                <div>
                    <div class="item-name">{{ $rdv->patient->nom ?? 'N/A' }} {{ $rdv->patient->prenom ?? '' }}</div>
                    <div class="item-sub">{{ $rdv->type ?? 'Consultation' }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-check-circle" style="color:#43a047;"></i>
                <p>Aucun rendez-vous à confirmer</p>
            </div>
        @endforelse
    </div>

    {{-- Agenda du jour --}}
    <div class="section">
        <div class="section-header">
            <i class="fas fa-sun" style="color: #fb8c00;"></i>
            <h3>Agenda du jour</h3>
        </div>
        @forelse($agenda_jour as $rdv)
            <div class="rdv-item">
                <div class="time-pill confirmed">{{ optional($rdv->date_heure)->format('H:i') }}</div>
                <div>
                    <div class="item-name">{{ $rdv->patient->nom ?? 'N/A' }} {{ $rdv->patient->prenom ?? '' }}</div>
                    <div class="item-sub">{{ $rdv->type ?? 'Consultation' }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Aucun rendez-vous aujourd'hui</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Nouveaux patients --}}
<div class="section">
    <div class="section-header">
        <i class="fas fa-user-plus" style="color: #8e24aa;"></i>
        <h3>Nouveaux patients enregistrés</h3>
    </div>
    @forelse($nouveaux_patients as $patient)
        <div class="patient-row">
            @php
                $initP = strtoupper(substr($patient->prenom ?? $patient->nom ?? 'P', 0, 1));
            @endphp
            <div class="patient-avatar">{{ $initP }}</div>
            <div>
                <div class="item-name">{{ $patient->nom }} {{ $patient->prenom }}</div>
                <div class="item-sub">
                    <i class="fas fa-envelope" style="font-size:0.7rem;"></i> {{ $patient->email }}
                    @if($patient->telephone)
                        &bull; <i class="fas fa-phone" style="font-size:0.7rem;"></i> {{ $patient->telephone }}
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-users" style="color:#90a4ae;"></i>
            <p>Aucun nouveau patient récemment</p>
        </div>
    @endforelse
</div>
@endsection
