<?php

namespace App\Modules\CNAM\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CNAM\Models\Soin;
use App\Modules\CNAM\Models\BordereauCnam;
use App\Modules\CNAM\Services\CnamService;
use Illuminate\Http\Request;

class CnamController extends Controller
{
    protected $cnamService;

    public function __construct(CnamService $cnamService)
    {
        $this->cnamService = $cnamService;
    }

    public function index()
    {
        $bordereaux = BordereauCnam::with('dentiste')->latest()->paginate(10);
        return view('cnam.index', compact('bordereaux'));
    }

    public function create()
    {
        $soinsDisponibles = Soin::where('statut', 'effectue')
            ->with('patient')
            ->get();
        return view('cnam.create', compact('soinsDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'soin_ids' => 'required|array',
            'soin_ids.*' => 'exists:soins,id'
        ]);

        try {
            $bordereau = $this->cnamService->genererBordereau($request->soin_ids, auth()->id());
            return redirect()->route($this->getRoutePrefix() . 'cnam.index')->with('success', 'Bordereau généré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function transmettre($id)
    {
        $this->cnamService->transmettreBordereau($id);
        return back()->with('success', 'Bordereau transmis à la CNAM.');
    }

    public function createSoin()
    {
        $patients = \App\Modules\Patient\Models\Patient::orderBy('nom')->get();
        return view('cnam.soins.create', compact('patients'));
    }

    public function storeSoin(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soins' => 'required|array|min:1',
            'soins.*.date' => 'required|date',
            'soins.*.dent' => 'nullable|string',
            'soins.*.code' => 'required|string',
            'soins.*.cotation' => 'nullable|string',
            'soins.*.montant' => 'required|numeric',
            'soins.*.type' => 'required|in:acte,prothese',
            'soins.*.designation' => 'required|string',
        ]);

        foreach ($data['soins'] as $soinData) {
            Soin::create([
                'patient_id' => $data['patient_id'],
                'dentiste_id' => auth()->id(),
                'date_soin' => $soinData['date'],
                'dent' => $soinData['dent'],
                'acte_code' => $soinData['code'],
                'cotation' => $soinData['cotation'],
                'montant' => $soinData['montant'],
                'designation' => $soinData['designation'],
                'type_soin' => $soinData['type'],
                'part_cnam' => $soinData['montant'] * 0.8, // Exemple de calcul
                'part_patient' => $soinData['montant'] * 0.2,
                'statut' => 'effectue'
            ]);
        }

        return redirect()->route($this->getRoutePrefix() . 'cnam.index')->with('success', 'Soins enregistrés avec succès.');
    }

    protected function getRoutePrefix()
    {
        return str_contains(request()->route()->getName(), 'admin') ? 'admin.' : (str_contains(request()->route()->getName(), 'medecin') ? 'medecin.' : '');
    }
}
