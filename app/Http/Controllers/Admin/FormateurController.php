<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Formateur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormateurController extends Controller
{
    public function index()
    {
        $formateurs = Formateur::all();
        return Inertia::render('Admin/Formateurs/Index', ['formateurs' => $formateurs]);
    }

    public function create()
    {
        return Inertia::render('Admin/Formateurs/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'specialite' => 'required',
            'telephone' => 'required',
            'dernier_diplome' => 'required',
        ]);

        Formateur::create($request->all());
        return redirect()->route('formateurs.index')->with('success', 'Formateur créé avec succès.');
    }

    public function edit(Formateur $formateur)
    {
        return Inertia::render('Admin/Formateurs/Edit', ['formateur' => $formateur]);
    }

    public function update(Request $request, Formateur $formateur)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'specialite' => 'required',
            'telephone' => 'required',
            'dernier_diplome' => 'required',
        ]);

        $formateur->update($request->all());
        return redirect()->route('formateurs.index')->with('success', 'Formateur mis à jour avec succès.');
    }

    public function destroy(Formateur $formateur)
    {
        $formateur->delete();
        return redirect()->route('formateurs.index')->with('success', 'Formateur supprimé avec succès.');
    }
}
