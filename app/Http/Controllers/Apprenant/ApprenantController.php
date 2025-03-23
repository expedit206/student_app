<?php

namespace App\Http\Controllers\Apprenant;

use App\Models\Note;
use Inertia\Inertia;
use App\Models\Apprenant;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprenantController extends Controller
{
    public function progression(Request $request)
    {
        $user = auth()->user();
        $apprenant = $user->apprenant;

        if (!$apprenant || !$apprenant->formation_id) {
            return inertia('Apprenants/Progression', [
                'progressionData' => [
                    'formation' => null,
                    'progression' => 0,
                    'disciplines' => [],
                ],
            ]);
        }

        $apprenantId = $apprenant->id;
        $formation = Formation::with(['disciplines', 'notes' => function ($query) use ($apprenantId) {
            $query->where('apprenant_id', $apprenantId);
        }])->find($apprenant->formation_id);

        if (!$formation) {
            return inertia('Apprenants/Progression', [
                'progressionData' => [
                    'formation' => null,
                    'progression' => 0,
                    'disciplines' => [],
                ],
            ]);
        }

        // Calcul de la progression
        $totalDisciplines = $formation->disciplines->count();
        $completedDisciplines = $formation->notes->pluck('discipline_id')->unique()->count();
        $progression = $totalDisciplines > 0 ? round(($completedDisciplines / $totalDisciplines) * 100, 1) : 0;

        // Détails des disciplines
        $disciplinesData = $formation->disciplines->map(function ($discipline) use ($formation, $apprenant) {
            $hasNote = $formation->notes->where('discipline_id', $discipline->id)
            ->where('apprenant_id', $apprenant->id) 
            ->isNotEmpty();
                return [
                'id' => $discipline->id,
                'name' => $discipline->nom,
                'completed' => $hasNote,
            ];
        });

        $formationData = [
            'id' => $formation->id,
            'name' => $formation->titre,
        ];

        return inertia('Apprenants/Progression', [
            'progressionData' => [
                'formation' => $formationData,
                'progression' => $progression,
                'disciplines' => $disciplinesData,
            ],
        ]);
    }


    public function carnet(Request $request)
    {
        $user = $request->user();
        $apprenant = Apprenant::where('user_id', $user->id)->firstOrFail();

        // Charger la formation avec les disciplines, formateurs et notes associées à l'apprenant
        $formation = $apprenant->formation()->with(['disciplines' => function ($query) use ($apprenant) {
            $query->with(['formateurs' => function ($q) use ($apprenant) {
                $q->select('formateurs.id', 'nom')->wherePivot('formation_id', $apprenant->formation_id);
            }, 'notes' => function ($q) use ($apprenant) {
                $q->where('apprenant_id', $apprenant->id)->select('discipline_id', 'note');
            }]);
        }])->firstOrFail();

        // Préparer les données des disciplines
        $disciplinesData = $formation->disciplines->map(function ($discipline) use ($apprenant) {
            $note = $discipline->notes()->where('apprenant_id', $apprenant->id) 
             ->first(); // Récupérer la première note pour cet apprenant
            return [
                'id' => $discipline->id,
                'titre' => $discipline->nom,
                'heures_hebdo' => $discipline->heures_hebdo ?? 0,
                'heures_total' => $discipline->heures_total ?? 0,
                'formateur' => $discipline->formateurs->first()->nom ?? 'Non attribué',
                'coefficient' => ($discipline->heures_total ?? 0) / 10, // Coefficient basé sur heures_total
                'note' => $note ? "{$note->note} / 20" : 'N/A', // Formatage sur 20
                'statut' => $note ? 'Terminé' : 'En cours',
            ];
        })->all();

        // Calcul dynamique de la moyenne_formation
        $totalNotesPondérées = $formation->disciplines->reduce(function ($carry, $discipline) {
            $note = $discipline->notes->first();
            $coefficient = ($discipline->heures_total ?? 0) / 10;
            return $carry + ($note ? $note->note * $coefficient : 0);
        }, 0);
// dd($totalNotesPondérées);
        $totalCoefficients = $formation->disciplines->sum(function ($discipline) {
            return ($discipline->heures_total ?? 0) / 10;
        });

        $moyenneFormation = $totalCoefficients > 0 ? round($totalNotesPondérées / $totalCoefficients, 2) : null;

        // Construction des données pour la vue
        $data['carnetData'] = [
            'titre' => $formation->titre,
            'ecole' => 'Centre de Formation Professionnel La Canadienne',
            'apprenant' => $user->name ?? $apprenant->nom, // Fallback si $user->name est null
            'annee_scolaire' => '2024-2025',
            'disciplines' => $disciplinesData,
            'total_heures' => $formation->disciplines->sum('heures_total'),
            'moyenne_formation' => $moyenneFormation,
        ];

        return Inertia::render('Apprenants/Carnet', $data);
    }
}
