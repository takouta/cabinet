<?php

namespace App\Modules\CNAM\Services;

use App\Modules\CNAM\Models\Soin;
use App\Modules\CNAM\Models\BordereauCnam;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CnamService
{
    /**
     * Génère un nouveau bordereau pour une liste de soins.
     */
    public function genererBordereau(array $soinIds, $dentisteId)
    {
        return DB::transaction(function () use ($soinIds, $dentisteId) {
            $soins = Soin::whereIn('id', $soinIds)
                ->where('statut', 'effectue')
                ->get();

            if ($soins->isEmpty()) {
                throw new \Exception("Aucun soin valide sélectionné.");
            }

            $montantTotal = $soins->sum('part_cnam');

            $bordereau = BordereauCnam::create([
                'numero_bs' => 'BS-' . now()->format('YmdHis'),
                'dentiste_id' => $dentisteId,
                'date_bordereau' => now(),
                'montant_total' => $montantTotal,
                'statut' => 'brouillon'
            ]);

            Soin::whereIn('id', $soinIds)->update([
                'bordereau_id' => $bordereau->id,
                'statut' => 'inclu_dans_bs'
            ]);

            return $bordereau;
        });
    }

    /**
     * Transmet le bordereau (simulation).
     */
    public function transmettreBordereau($bordereauId)
    {
        $bordereau = BordereauCnam::findOrFail($bordereauId);
        $bordereau->update(['statut' => 'transmis']);
        return $bordereau;
    }
}
