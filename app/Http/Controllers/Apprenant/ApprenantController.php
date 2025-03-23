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
        $disciplinesData = $formation->disciplines->map(function ($discipline) use ($formation) {
            $hasNote = $formation->notes->where('discipline_id', $discipline->id)
            ->where('apprenant_id', $formation->apprenant_id) 
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

        $formation = $apprenant->formation()->with(['disciplines' => function ($query) use ($apprenant) {
            $query->with(['formateurs' => function ($q) use ($apprenant) {
                $q->select('formateurs.id', 'nom')->wherePivot('formation_id', $apprenant->formation_id);
            }]);
        }])->firstOrFail();
        $data['carnetData'] = [
            'titre' => $formation->titre,
            'ecole' => 'Centre de formation Professionnel la Canadienne', // À remplacer par une valeur dynamique si nécessaire
            'apprenant' => $user->apprenant->nom,
            'annee_scolaire' => '2024-2025', // À dynamiser (ex. via une config ou DB)
            'disciplines' => $formation->disciplines->map(function ($discipline) use($apprenant) {
                return [
                    'id' => $discipline->id,
                    'titre' => $discipline->nom,
                    'heures_hebdo' => $discipline->heures_hebdo ?? 0,
                    'heures_total' => $discipline->heures_total ?? 0,
                    'formateur' => $discipline->formateurs->first()->nom ?? 'Non attribué',
                    'coefficient' => ($discipline->heures_total ?? 0) / 10,
                    'note' => $discipline->notes()->where('apprenant_id', $apprenant->id)->pluck('note') ?? 'N/A', // À ajuster selon votre modèle de notes
                    'statut' => $discipline->notes()->where('apprenant_id', $apprenant->id)->pluck('note') ?'Terminé': 'En cours', // À ajuster selon votre logique
                ];
            })->all(),
            'total_heures' => $formation->disciplines->sum('heures_total'),
            'moyenne_formation' => $formation->moyenne ?? null, // À calculer si nécessaire
        ];

        return Inertia::render('Apprenants/Carnet', $data);
    }
}
