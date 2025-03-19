<?php

namespace App\Http\Controllers\Apprenant;

use Inertia\Inertia;
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
            $hasNote = $formation->notes->where('discipline_id', $discipline->id)->isNotEmpty();
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

}
