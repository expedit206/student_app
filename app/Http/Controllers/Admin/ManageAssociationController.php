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
        $formateur->load('disciplines'); // Charge les disciplines avec formation_id via le pivot
        $disciplines = Discipline::select(['id', 'nom'])->get();
        $formations = Formation::with('disciplines')->get();

        // Regrouper les disciplines du formateur par formation_id depuis discipline_formateur
        $formateurDisciplinesByFormation = $formateur->disciplines
            ->groupBy('pivot.formation_id')
            ->map(function ($disciplines, $formationId) {
                return [
                    'formation_id' => $formationId,
                    'disciplines' => $disciplines->map->only(['id', 'nom']),
                ];
            })->values();

        // Charger les formations correspondantes avec leurs disciplines associées au formateur
        $formateurFormations = Formation::whereIn('id', $formateurDisciplinesByFormation->pluck('formation_id'))
            ->get()
            ->map(function ($formation) use ($formateurDisciplinesByFormation) {
                $disciplinesForFormation = $formateurDisciplinesByFormation
                    ->firstWhere('formation_id', $formation->id);
                return [
                    'id' => $formation->id,
                    'titre' => $formation->titre,
                    'disciplines' => $disciplinesForFormation ? $disciplinesForFormation['disciplines'] : [],
                ];
            });

        return inertia('Admin/ManageAssociations/FomateurFormationDisciplineAssociations', [
            'formateur' => [
                'id' => $formateur->id,
                'nom' => $formateur->nom,
                'disciplines' => $formateur->disciplines->map(function ($discipline) {
                    return [
                        'id' => $discipline->id,
                        'nom' => $discipline->nom,
                        'formation_id' => $discipline->pivot->formation_id,
                    ];
                }),
            ],
            'disciplines' => $disciplines,
            'formations' => $formations->map(function ($formation) {
                return [
                    'id' => $formation->id,
                    'titre' => $formation->titre,
                    'disciplines' => $formation->disciplines->map->only(['id', 'nom']),
                ];
            }),
            'formateurFormations' => $formateurFormations,
        ]);
    }


    public function updateFormateurFormationDisciplineAssociations(Request $request, Formateur $formateur)
    {
        $validated = $request->validate([
            'discipline_ids' => 'array',
            'discipline_ids.*' => 'exists:disciplines,id',
            'formation_id' => 'required|exists:formations,id',
        ]);

        // Préparer les données pour discipline_formateur
        $syncData = collect($validated['discipline_ids'])->mapWithKeys(function ($discipline_id) use ($validated) {
            return [$discipline_id => ['formation_id' => $validated['formation_id']]];
        })->all();

        // Synchroniser les disciplines pour cette formation dans discipline_formateur
        $formateur->disciplines()->wherePivot('formation_id', $validated['formation_id'])->sync($syncData);

        // Mettre à jour formateur_formation : associer la formation au formateur si des disciplines sont sélectionnées
        if (!empty($validated['discipline_ids'])) {
            $formateur->formations()->syncWithoutDetaching([$validated['formation_id']]);
        } else {
            // Si aucune discipline n'est sélectionnée, vérifier si d'autres disciplines existent pour cette formation
            $hasOtherDisciplines = $formateur->disciplines()
                ->wherePivot('formation_id', $validated['formation_id'])
                ->exists();

            if (!$hasOtherDisciplines) {
                $formateur->formations()->detach($validated['formation_id']);
            }
        }

        return redirect()->route('formateurs.manageAssociations', $formateur)
            ->with('success', 'Associations mises à jour avec succès.');
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
