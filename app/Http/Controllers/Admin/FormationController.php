<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Controller;
use App\Models\Formateur;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = Formation::with('formateurs')->orderBy('id', 'desc')->get();
        return Inertia::render('Admin/Formations/Index', [
            'formations' => $formations,
        ]);
    }

    // Afficher le formulaire de création
    public function create()
    {
        $formateurs = Formateur::all();
        return Inertia::render('Admin/Formations/Create', [
            'formateurs' => $formateurs,
        ]);
    }

    // Stocker une nouvelle formation
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
         ]);

        Formation::create($request->all()); // Crée la formation avec toutes les données validées
        return redirect()->route('formations.index')->with('success', 'Formation créée avec succès.');
    }

    // Afficher le formulaire d'édition
    public function edit(Formation $formation)
    {
        $formateurs = Formateur::all();
        return Inertia::render('Admin/Formations/Edit', [
            'formation' => $formation,
            'formateurs' => $formateurs,
        ]);
    }

    // Mettre à jour une formation existante
    public function update(Request $request, Formation $formation)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $formation->update($request->all());
        return redirect()->route('formations.index')->with('success', 'Formation mise à jour avec succès.');
    }

    // Supprimer une formation
    public function destroy(Formation $formation)
    {
        $formation->delete();
        return redirect()->route('formations.index')->with('success', 'Formation supprimée avec succès.');
    }
}
