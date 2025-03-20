<?php

namespace App\Http\Controllers\Formateur;

use App\Models\Note;
use Inertia\Inertia;
use App\Models\Apprenant;
use App\Models\Formation;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function showNotes(Request $request)
    {
        $formateurId = auth()->user()->formateur->id;

        // Étape 1 : Récupérer les disciplines assignées au formateur, groupées par formation_id
        $disciplineFormateurRaw = DB::table('discipline_formateur')
            ->where('formateur_id', $formateurId)
            ->select('formation_id', 'discipline_id')
            ->get();

        // Regrouper les discipline_id par formation_id
        $disciplineFormateur = $disciplineFormateurRaw->groupBy('formation_id')->map(function ($group) {
            return $group->pluck('discipline_id')->all();
        })->toArray();

        // Extraire les IDs de formations
        $formationIds = array_keys($disciplineFormateur);

        // Étape 2 : Charger les formations avec leurs disciplines et apprenants
        $formationsRaw = Formation::whereIn('id', $formationIds)
            ->with([
                'disciplines' => function ($query) use ($disciplineFormateur) {
                    $query->whereIn('disciplines.id', array_merge(...array_values($disciplineFormateur)));
                },
                'apprenants' => function ($query) {
                    $query->with(['notes' => function ($query) {
                        $query->select('id', 'apprenant_id', 'formation_id', 'discipline_id', 'note');
                    }]);
                }
            ])
            ->get();

        // Déterminer la formation sélectionnée par défaut
        $selectedFormationId = $formationsRaw->isNotEmpty() ? $formationsRaw->first()->id : null;

        // Étape 3 : Transformer les données pour Inertia
        $formations = $formationsRaw->map(function ($formation) use ($disciplineFormateur) {
            // Filtrer les disciplines pour ne garder que celles assignées au formateur pour cette formation
            $filteredDisciplines = $formation->disciplines->filter(function ($discipline) use ($formation, $disciplineFormateur) {
                return in_array($discipline->id, $disciplineFormateur[$formation->id] ?? []);
            });

            return [
                'id' => $formation->id,
                'titre' => $formation->titre,
                'disciplines' => $filteredDisciplines->map(fn($discipline) => [
                    'id' => $discipline->id,
                    'nom' => $discipline->nom,
                ]),
                'apprenants' => $formation->apprenants->map(function ($apprenant) use ($formation) {
                    $notes = $apprenant->notes->where('formation_id', $formation->id)
                        ->mapWithKeys(fn($note) => [$note->discipline_id => $note->note]);
                    return [
                        'id' => $apprenant->id,
                        'nom' => $apprenant->nom,
                        'prenom' => $apprenant->prenom,
                        'notes' => $notes->all(),
                    ];
                }),
            ];
        });

        return Inertia::render('Formateurs/Notes/Index', [
            'formations' => $formations,
            'selectedFormationId' => $selectedFormationId,
        ]);
    }

    public function updateNote(Request $request)
    {
        $request->validate([
            'apprenant_id' => 'required|exists:apprenants,id',
            'formation_id' => 'required|exists:formations,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'note' => 'required|numeric|min:0|max:20',
        ]);

        $formateurId = auth()->user()->id;
        $formation = Formation::where('id', $request->formation_id)
            ->whereHas('formateurs', function ($query) use ($formateurId) {
                $query->where('formateur_id', $formateurId);
            })->firstOrFail();

        if (!$formation->disciplines()->where('discipline_id', $request->discipline_id)->exists()) {
            return redirect()->back()->withErrors(['discipline_id' => 'Cette discipline n’est pas associée à la formation.']);
        }

        // Vérifier si la note existe déjà pour distinguer création et mise à jour
        $existingNote = Note::where([
            'apprenant_id' => $request->apprenant_id,
            'formation_id' => $request->formation_id,
            'discipline_id' => $request->discipline_id,
        ])->first();

        // Mettre à jour ou créer la note
        $note = Note::updateOrCreate(
            [
                'apprenant_id' => $request->apprenant_id,
                'formation_id' => $request->formation_id,
                'discipline_id' => $request->discipline_id,
            ],
            ['note' => $request->note]
        );

        // Récupérer l'apprenant et son utilisateur associé
        $apprenant = Apprenant::find($request->apprenant_id);
        $user = $apprenant->user;

        // Charger la discipline pour le message
        $discipline = $formation->disciplines()->where('discipline_id', $request->discipline_id)->first();

        // Générer un message en fonction de la création ou mise à jour
        $message = $existingNote
            ? "Votre note pour {$discipline->nom} a été mise à jour : {$request->note}/20"
            : "Nouvelle note reçue pour {$discipline->nom} : {$request->note}/20";

        // Créer la notification
        $user->notifications()->create([
            'type' => 'note_update',
            'message' => $message,
            'data' => [
                'note_id' => $note->id,
                'formation_id' => $formation->id,
                'discipline_id' => $discipline->id,
            ],
        ]);

        return redirect()->back()->with('success', 'Note mise à jour avec succès.');
    }

}
