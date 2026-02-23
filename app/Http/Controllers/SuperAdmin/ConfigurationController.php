<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = Configuration::orderBy('key')->paginate(20);

        return view('super_admin.configurations.index', compact('configurations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);

        Configuration::updateOrCreate(
            ['key' => $validated['key']],
            ['value' => $validated['value'], 'updated_by' => auth()->id()]
        );

        return redirect()->route('super_admin.configurations.index')
            ->with('success', 'Configuration mise a jour.');
    }
}
