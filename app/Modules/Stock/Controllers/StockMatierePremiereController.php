<?php

namespace App\Modules\Stock\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stock\Models\StockMatierePremiere;
use Illuminate\Http\Request;

class StockMatierePremiereController extends Controller
{
    public function index()
    {
        $stocks = StockMatierePremiere::with('fournisseur')
            ->orderBy('nom')
            ->paginate(20)
            ->withQueryString();
        return view('stock.index', compact('stocks'));
    }

    public function create()
    {
        return view('stock.create');
    }

    public function store(Request $request)
    {
        $this->prepareNumericFields($request);

        $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|numeric|min:0',
            'unite' => 'nullable|string|max:50',
            'fournisseur' => 'nullable|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'emplacement' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        StockMatierePremiere::create($request->all());

        return redirect()->route('stock.index')->with('success', 'Materiel ajoute au stock avec succes!');
    }

    public function show($id)
    {
        $stock = StockMatierePremiere::findOrFail($id);
        return view('stock.show', compact('stock'));
    }

    public function edit($id)
    {
        $stock = StockMatierePremiere::findOrFail($id);
        return view('stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $this->prepareNumericFields($request);

        $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|numeric|min:0',
            'unite' => 'nullable|string|max:50',
            'fournisseur' => 'nullable|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'emplacement' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $stock = StockMatierePremiere::findOrFail($id);
        $stock->update($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock mis a jour avec succes!');
    }

    public function destroy($id)
    {
        $stock = StockMatierePremiere::findOrFail($id);
        $stock->delete();

        return redirect()->route('stock.index')->with('success', 'Materiel supprime avec succes!');
    }

    private function prepareNumericFields(Request $request)
    {
        $fields = ['quantite', 'seuil_alerte', 'prix_unitaire'];
        foreach ($fields as $field) {
            if ($request->has($field) && is_string($request->$field)) {
                $request->merge([
                    $field => str_replace(',', '.', $request->$field)
                ]);
            }
        }
    }
}
