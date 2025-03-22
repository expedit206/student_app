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


    public function carnet(Request $request)
    {
        $user = $request->user();
        $apprenant = Apprenant::where('user_id', $user->id)->firstOrFail();

        $formation = $apprenant->formation()
            ->with(['disciplines.formateurs', 'notes' => function ($query) use ($apprenant) {
                $query->where('apprenant_id', $apprenant->id)
                    ->select('id', 'apprenant_id', 'discipline_id', 'note', 'created_at');
            }])
            ->first();

        if (!$formation) {
            return Inertia::render('Apprenant/Carnet', ['carnetData' => null]);
        }
        $formation = $formation->load('notes');
        $disciplines = $formation->disciplines->map(function ($discipline) use ($formation , $apprenant) {
            $notes = $formation->notes->where('discipline_id', $discipline->id)->pluck('note');
            // $formations = $apprenant->formation()->with('notes')->get();
            // dd($formation->notes);
            $moyenne = $notes->avg();

            // Récupérer le formateur lié à cette discipline ET cette formation
            $formateur = $discipline->formateurs()
                ->wherePivot('formation_id', $formation->id)
                ->first();

            return [
                'id' => $discipline->id,
                'titre' => $discipline->nom,
                'heures_hebdo' => $discipline->heures_hebdo ?? 0,
                'heures_total' => $discipline->heures_total ?? 0,
                'formateur' => $formateur ? $formateur->nom : 'Non attribué',
                'coefficient' => ($discipline->heures_total ?? 0) / 10,
                'notes' => $notes->implode(', ') ?: 'Aucune',
                'moyenne' => $moyenne ? round($moyenne, 2) : null,
                'statut' => $moyenne ? ($moyenne >= 10 ? 'Validé' : 'À améliorer') : 'N/A',
            ];
        });

        $carnetData = [
            'titre' => $formation->titre,
            'disciplines' => $disciplines,
            'moyenne_formation' => $disciplines->pluck('moyenne')->filter()->avg(),
            'total_heures' => $disciplines->sum('heures_total'),
        ];

        return Inertia::render('Apprenants/Carnet', [
            'carnetData' => $carnetData,
        ]);
    }
}
