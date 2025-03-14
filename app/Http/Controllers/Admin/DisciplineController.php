<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisciplineController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::all(); // Ajoutez le tri si nécessaire
        return inertia('Disciplines/Index', ['disciplines' => $disciplines]);
    }

    public function create()
    {
        return inertia('Disciplines/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_discipline' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Discipline::create($validated);
        return redirect()->route('disciplines.index')->with('success', 'Discipline créée avec succès.');
    }

    public function edit($id)
    {
        $discipline = Discipline::findOrFail($id);
        return inertia('Disciplines/Edit', ['discipline' => $discipline]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom_discipline' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $discipline = Discipline::findOrFail($id);
        $discipline->update($validated);
        return redirect()->route('disciplines.index')->with('success', 'Discipline mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $discipline = Discipline::findOrFail($id);
        $discipline->delete();
        return redirect()->route('disciplines.index')->with('success', 'Discipline supprimée avec succès.');
    }
}
