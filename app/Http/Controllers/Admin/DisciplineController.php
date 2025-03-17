<?php

namespace App\Http\Controllers\Admin;

use App\Models\Formation;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisciplineController extends Controller
{

    public function index()
    {
        $disciplines = Discipline::with('formations')->orderBy('id', 'desc')->get();
        $formations = Formation::all();

        return inertia('Admin/Disciplines/Index', [
            'disciplines' => $disciplines,
            'formations' => $formations,
        ]);
    }

    public function create()
    {
        return inertia('Admin/Disciplines/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
// dd($validated);
        Discipline::create($validated);
        return redirect()->route('disciplines.index')->with('success', 'Discipline créée avec succès.');
    }

    public function edit($id)
    {
        $discipline = Discipline::findOrFail($id);
        return inertia('Admin/Disciplines/Edit', ['discipline' => $discipline]);
    }

    public function update(Request $request,Discipline $discipline)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

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
