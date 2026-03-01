<?php

namespace App\Modules\RendezVous\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\RendezVous\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezvous = RendezVous::with('patient')->orderBy('date_heure', 'desc')->get();
        $routePrefix = $this->getRoutePrefix();
        return view('rendezvous.index', compact('rendezvous', 'routePrefix'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nom')->get();
        $routePrefix = $this->getRoutePrefix();
        return view('rendezvous.create', compact('patients', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'statut' => 'required|in:confirme,annule,en_attente',
            'type_consultation' => 'required|in:premiere_visite,controle,traitement,urgence',
        ]);

        $validated['dentiste_id'] = Auth::id() ?? 1;

        RendezVous::create($validated);

        return redirect()->route($this->getRoutePrefix() . '.rendezvous.index')->with('success', 'Rendez-vous cree avec succes!');
    }

    public function show(RendezVous $rendezvous)
    {
        $routePrefix = $this->getRoutePrefix();
        return view('rendezvous.show', compact('rendezvous', 'routePrefix'));
    }

    public function edit(RendezVous $rendezvous)
    {
        $patients = Patient::orderBy('nom')->get();
        $routePrefix = $this->getRoutePrefix();
        return view('rendezvous.edit', compact('rendezvous', 'patients', 'routePrefix'));
    }

    public function update(Request $request, RendezVous $rendezvous)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_heure' => 'required|date',
            'motif' => 'required|string|max:500',
            'statut' => 'required|in:confirme,annule,en_attente',
            'type_consultation' => 'required|in:premiere_visite,controle,traitement,urgence',
        ]);

        $rendezvous->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.rendezvous.index')->with('success', 'Rendez-vous modifie avec succes!');
    }

    public function destroy(RendezVous $rendezvous)
    {
        $prefix = $this->getRoutePrefix();
        $rendezvous->delete();

        return redirect()->route($prefix . '.rendezvous.index')->with('success', 'Rendez-vous supprime avec succes!');
    }

    /**
     * Determine the route prefix based on the current route name or authenticated user role.
     */
    private function getRoutePrefix()
    {
        $routeName = request()->route()->getName();
        if ($routeName && strpos($routeName, '.') !== false) {
            return explode('.', $routeName)[0];
        }

        $user = Auth::user();
        if (!$user) return 'patient';

        $rolePrefixes = [
            'admin_cabinet' => 'admin',
            'admin' => 'admin',
            'medecin' => 'medecin',
            'dentiste' => 'medecin',
            'secretaire' => 'secretaire',
            'patient' => 'patient',
            'assistant' => 'patient',
        ];

        return $rolePrefixes[$user->role] ?? 'patient';
    }
}