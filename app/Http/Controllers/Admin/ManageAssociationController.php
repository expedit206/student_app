<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formateur;
use App\Models\Discipline;
use App\Models\Formation;

class ManageAssociationController extends Controller
{

    public function manageFormateurFormationDisciplineAssociations(Formateur $formateur)
    {
        $formateur->load('disciplines'); // Charge les disciplines avec le pivot
        $disciplines = Discipline::select(['id', 'nom_discipline'])->get();
        $formations = Formation::with('disciplines')->get();

        return inertia('Admin/Formateurs/ManageAssociations', [
            'formateur' => [
                'id' => $formateur->id,
                'nom' => $formateur->nom,
                'disciplines' => $formateur->disciplines->map(function ($discipline) {
                    return [
                        'id' => $discipline->id,
                        'nom_discipline' => $discipline->nom_discipline,
                        'formation_id' => $discipline->pivot->formation_id,
                    ];
                }),
            ],
            'disciplines' => $disciplines,
            'formations' => $formations->map(function ($formation) {
                return [
                    'id' => $formation->id,
                    'titre' => $formation->titre,
                    'disciplines' => $formation->disciplines->map->only(['id', 'nom_discipline']),
                ];
            }),
        ]);
    }

    public function updateFormateurFormationDisciplineAssociations(Request $request,Formateur $formateur)
    {


        $validated = $request->validate([
            'discipline_ids' => 'array',
            'discipline_ids.*' => 'exists:disciplines,id',
            'formation_id' => 'required|exists:formations,id', // Ajout pour savoir quelle formation est concernée
        ]);

        // Préparer les données pour la synchronisation avec formation_id
        $syncData = collect($validated['discipline_ids'])->mapWithKeys(function ($discipline_id) use ($validated) {
            return [$discipline_id => ['formation_id' => $validated['formation_id']]];
        })->all();

        $formateur->disciplines()->sync($syncData);

        return redirect()->route('formateurs.index')->with('success', 'Associations mises à jour avec succès.');
     }

    public function manageFormationDisciplineAssociations(Formation $formation)
    {
        $formation = $formation->load('disciplines');
        $disciplines = Discipline::orderBy('id', 'desc')->get();
        return inertia('Admin/ManageAssociations/FormationDisciplineAssociation', [
            'formation' => [
                'id' => $formation->id,
                'titre' => $formation->titre,
                'disciplines' => $formation->disciplines->map->only(['id', 'nom']),
            ],
            'disciplines' => $disciplines,
        ]);
    }

    public function updateFormationDisciplineAssociations(Request $request, Formation $formation)
    {

        $validated = $request->validate([
            'discipline_ids' => 'array',
            'discipline_ids.*' => 'exists:disciplines,id',
        ]);

        $formation->disciplines()->sync($validated['discipline_ids'] ?? []);

        return redirect()->route('formations.index')->with('success', 'Disciplines associées mises à jour avec succès.');
    }
}
