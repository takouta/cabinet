<?php

namespace App\Modules\Fournisseur\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Fournisseur\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:fournisseurs',
            'telephone' => 'required|regex:/^[0-9]+$/|min:8|max:20',
            'adresse' => 'required|string',
            'specialite' => 'required|string',
        ]);

        Fournisseur::create($request->all());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur cree avec succes!');
    }

    public function show(Fournisseur $fournisseur)
    {
        $stocks = $fournisseur->stocks;
        return view('fournisseurs.show', compact('fournisseur', 'stocks'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:fournisseurs,email,' . $fournisseur->id,
            'telephone' => 'required|regex:/^[0-9]+$/|min:8|max:20',
            'adresse' => 'required|string',
            'specialite' => 'required|string',
        ]);

        $fournisseur->update($request->all());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur modifie avec succes!');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprime avec succes!');
    }
}
