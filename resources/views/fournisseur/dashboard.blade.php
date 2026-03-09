@extends('layouts.dashboard')

@section('title', 'Dashboard Fournisseur')
@section('page-title', 'Tableau de bord Fournisseur')

@section('content')
<div style="padding: 1.5rem 0;">

    {{-- En-tête de bienvenue --}}
    <div style="background: linear-gradient(135deg, #1a237e 0%, #1565c0 100%); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="margin: 0 0 0.4rem; font-size: 1.6rem; font-weight: 700;">
                Bienvenue, {{ auth()->user()->prenom ?? auth()->user()->nom ?? 'Fournisseur' }} 👋
            </h2>
            <p style="margin: 0; opacity: 0.8; font-size: 0.95rem;">
                {{ $fournisseur ? $fournisseur->nom : 'Espace Fournisseur' }} — {{ $fournisseur ? $fournisseur->email : '' }}
            </p>
        </div>
        <div style="opacity: 0.15; font-size: 5rem;">
            <i class="fas fa-truck-loading"></i>
        </div>
    </div>

    {{-- Cartes statistiques --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 14px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); border-left: 4px solid #1565c0;">
            <div style="color: #90a4ae; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Mon Catalogue</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #1a237e; line-height: 1;">{{ $stats['total_produits'] ?? 0 }}</div>
            <div style="color: #78909c; font-size: 0.82rem; margin-top: 0.4rem;">Produits référencés</div>
        </div>
        <div style="background: white; border-radius: 14px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); border-left: 4px solid #43a047;">
            <div style="color: #90a4ae; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Mes Cabinets Clients</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #2e7d32; line-height: 1;">{{ $stats['total_cabinets'] ?? 0 }}</div>
            <div style="color: #78909c; font-size: 0.82rem; margin-top: 0.4rem;">Cabinets de soins associés</div>
        </div>
        <div style="background: white; border-radius: 14px; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); border-left: 4px solid #ef5350;">
            <div style="color: #90a4ae; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Alertes Stock Cabinet</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #c62828; line-height: 1;">{{ $stats['produits_alerte'] ?? 0 }}</div>
            <div style="color: #78909c; font-size: 0.82rem; margin-top: 0.4rem;">Articles à réapprovisionner</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        {{-- Section: MON CATALOGUE PRODUITS --}}
        <div>
            <div style="background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 2rem;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f0f4f8; display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1a237e; margin: 0;">
                        <i class="fas fa-box" style="margin-right: 8px; color: #1565c0;"></i>
                        Mon Catalogue Produits
                    </h3>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="{{ route('fournisseur.produits.create') }}" style="background: #1565c0; color: white; padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: background 0.2s;">
                            <i class="fas fa-plus mr-1"></i> Ajouter un produit
                        </a>
                        <span style="background: #e3f2fd; color: #1565c0; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            {{ ($produits ?? collect())->count() }} produits
                        </span>
                    </div>
                </div>

                @if($produits->isEmpty())
                    <div style="padding: 3rem; text-align: center; color: #b0bec5;">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        <p>Votre catalogue de produits est vide.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 0.75rem 1.25rem; text-align: left; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Produit</th>
                                    <th style="padding: 0.75rem 1.25rem; text-align: center; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Réf.</th>
                                    <th style="padding: 0.75rem 1.25rem; text-align: center; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Prix Unitaire</th>
                                    <th style="padding: 0.75rem 1.25rem; text-align: center; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Unité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $p)
                                <tr style="border-bottom: 1px solid #f0f4f8;">
                                    <td style="padding: 1rem 1.25rem; font-weight: 600; color: #263238;">{{ $p->nom }}</td>
                                    <td style="padding: 1rem 1.25rem; text-align: center; color: #78909c;">{{ $p->reference }}</td>
                                    <td style="padding: 1rem 1.25rem; text-align: center; font-weight: 600;">{{ number_format($p->prix_unitaire, 3) }} DT</td>
                                    <td style="padding: 1rem 1.25rem; text-align: center; color: #90a4ae;">{{ $p->unite }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Section: STOCKS FOURNIS AU CABINET (POUR SURVEILLANCE) --}}
            <div style="background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f0f4f8; display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #c62828; margin: 0;">
                        <i class="fas fa-warehouse" style="margin-right: 8px; color: #e53935;"></i>
                        Surveillance Stocks Cabinet
                    </h3>
                </div>

                @if($stocks_cabinet->isEmpty())
                    <div style="padding: 3rem; text-align: center; color: #b0bec5;">
                        <p>Aucun stock fourni ne nécessite de surveillance.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 0.75rem 1.25rem; text-align: left; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Nom Produit</th>
                                    <th style="padding: 0.75rem 1.25rem; text-align: center; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Stock Actuel</th>
                                    <th style="padding: 0.75rem 1.25rem; text-align: center; color: #546e7a; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stocks_cabinet as $s)
                                <tr style="border-bottom: 1px solid #f0f4f8;">
                                    <td style="padding: 1rem 1.25rem; font-weight: 600;">{{ $s->nom }}</td>
                                    <td style="padding: 1rem 1.25rem; text-align: center;">
                                        {{ number_format($s->quantite, 2) }} {{ $s->unite_mesure }}
                                    </td>
                                    <td style="padding: 1rem 1.25rem; text-align: center;">
                                        @if($s->quantite <= ($s->seuil_alerte ?? 5))
                                            <span style="background: #ffebee; color: #c62828; padding: 0.2rem 0.6rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">ALERTE</span>
                                        @else
                                            <span style="background: #e8f5e9; color: #2e7d32; padding: 0.2rem 0.6rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">OK</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Section: MES CABINETS CLIENTS --}}
        <div>
            <div style="background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f0f4f8; background: #fafafa;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #2e7d32; margin: 0;">
                        <i class="fas fa-hospital-user" style="margin-right: 8px; color: #43a047;"></i>
                        Mes Cabinets Clients
                    </h3>
                </div>
                <div style="padding: 1.5rem;">
                    @if($cabinets->isEmpty())
                        <p style="text-align: center; color: #90a4ae; font-size: 0.9rem;">Aucun cabinet client enregistré.</p>
                    @else
                        @foreach($cabinets as $c)
                        <div style="background: #f8fafc; border-radius: 10px; padding: 1rem; margin-bottom: 1rem; border: 1px solid #e0e0e0; display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #e8f5e9; color: #2e7d32; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="fas fa-clinic-medical"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #263238; margin-bottom: 0.2rem;">{{ $c->nom }}</div>
                                <div style="font-size: 0.8rem; color: #78909c; margin-bottom: 0.4rem;">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i> {{ $c->adresse ?? 'Adresse non spécifiée' }}
                                </div>
                                <div style="font-size: 0.8rem; color: #78909c;">
                                    <i class="fas fa-phone-alt" style="margin-right: 4px;"></i> {{ $c->telephone ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
