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
            return redirect()->route('cnam.index')->with('success', 'Bordereau généré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function transmettre($id)
    {
        $this->cnamService->transmettreBordereau($id);
        return back()->with('success', 'Bordereau transmis à la CNAM.');
    }
}
