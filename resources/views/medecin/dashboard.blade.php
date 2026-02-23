@extends('layouts.dashboard')

@section('title', 'Espace Médecin')
@section('page-title', 'Tableau de bord Médecin')
@section('page-subtitle', "Gérez vos patients et vos rendez-vous")

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
    .stat-card.red { border-left-color: #e53935; }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; background: rgba(2,136,209,0.1); color: #0288d1; flex-shrink:0;
    }
    .stat-card.green .stat-icon { background: rgba(67,160,71,0.1); color: #43a047; }
    .stat-card.orange .stat-icon { background: rgba(251,140,0,0.1); color: #fb8c00; }
    .stat-card.red .stat-icon { background: rgba(229,57,53,0.1); color: #e53935; }
    .stat-val { font-size: 1.7rem; font-weight: 700; color: #1a237e; }
    .stat-lbl { font-size: 0.8rem; color: #78909c; }

    .two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
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
    .rdv-time-badge {
        background: linear-gradient(135deg, #0288d1, #1565c0);
        color: white; border-radius: 8px; padding: 0.35rem 0.65rem;
        font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
    }
    .rdv-time-badge.today {
        background: linear-gradient(135deg, #43a047, #2e7d32);
    }
    .rdv-patient-name { font-weight: 600; color: #263238; font-size: 0.9rem; }
    .rdv-meta { font-size: 0.78rem; color: #78909c; margin-top: 0.1rem; }

    .empty-state { padding: 2rem; text-align: center; color: #b0bec5; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }

    .welcome-banner {
        background: linear-gradient(135deg, #1565c0, #0288d1);
        border-radius: 14px; padding: 1.5rem; color: white;
        margin-bottom: 1.75rem; display: flex; align-items: center; gap: 1.25rem;
    }
    .welcome-banner .icon-wrap {
        width: 56px; height: 56px; border-radius: 14px;
        background: rgba(255,255,255,0.2); display: flex;
        align-items: center; justify-content: center; font-size: 1.8rem;
    }
    .welcome-banner h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.1rem; }
    .welcome-banner p { font-size: 0.85rem; opacity: 0.85; }
</style>

{{-- Bannière de bienvenue --}}
<div class="welcome-banner">
    <div class="icon-wrap"><i class="fas fa-user-md"></i></div>
    <div>
        <h4>Bienvenue Dr. {{ Auth::user()->name }}</h4>
        <p>Bonne journée de consultation — {{ now()->translatedFormat('l d F Y') }}</p>
    </div>
</div>

{{-- Stats --}}
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val">{{ $stats['patients_total'] }}</div>
            <div class="stat-lbl">Patients suivis</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div>
            <div class="stat-val">{{ $stats['rdv_mois'] }}</div>
            <div class="stat-lbl">RDV ce mois</div>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-val">{{ count($rdv_aujourdhui) }}</div>
            <div class="stat-lbl">RDV aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-user-times"></i></div>
        <div>
            <div class="stat-val">{{ $stats['taux_absentisme'] }}%</div>
            <div class="stat-lbl">Taux absentéisme</div>
        </div>
    </div>
</div>

<div class="two-cols">
    {{-- RDV d'aujourd'hui --}}
    <div class="section">
        <div class="section-header">
            <i class="fas fa-sun" style="color: #fb8c00;"></i>
            <h3>Rendez-vous aujourd'hui</h3>
        </div>
        @forelse($rdv_aujourdhui as $rdv)
            <div class="rdv-item">
                <div class="rdv-time-badge today">{{ optional($rdv->date_heure)->format('H:i') }}</div>
                <div>
                    <div class="rdv-patient-name">{{ $rdv->patient->nom ?? 'N/A' }} {{ $rdv->patient->prenom ?? '' }}</div>
                    <div class="rdv-meta"><i class="fas fa-stethoscope" style="font-size:0.7rem;"></i> {{ $rdv->type ?? 'Consultation' }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-coffee"></i>
                <p>Aucun rendez-vous aujourd'hui</p>
            </div>
        @endforelse
    </div>

    {{-- Prochains RDV --}}
    <div class="section">
        <div class="section-header">
            <i class="fas fa-calendar-alt" style="color: #0288d1;"></i>
            <h3>Prochains rendez-vous</h3>
        </div>
        @forelse($prochains_rdv as $rdv)
            <div class="rdv-item">
                <div class="rdv-time-badge">{{ optional($rdv->date_heure)->format('d/m H:i') }}</div>
                <div>
                    <div class="rdv-patient-name">{{ $rdv->patient->nom ?? 'N/A' }} {{ $rdv->patient->prenom ?? '' }}</div>
                    <div class="rdv-meta"><i class="fas fa-stethoscope" style="font-size:0.7rem;"></i> {{ $rdv->type ?? 'Consultation' }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-calendar-check" style="color:#43a047;"></i>
                <p>Aucun rendez-vous à venir</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
