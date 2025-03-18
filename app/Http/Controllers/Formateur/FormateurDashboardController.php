<?php

namespace App\Http\Controllers\Formateur;

use App\Models\Note;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormateurDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $formateurId = auth()->user()->id;

        // Charger les formations gérées par le formateur
        $formations = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })
            ->with(['apprenants', 'notes'])
            ->get()
            ->map(function ($formation) {
                $totalNotes = $formation->notes->whereIn('apprenant_id', $formation->apprenants->pluck('id'));
                $moyenne = $totalNotes->isNotEmpty() ? round($totalNotes->avg('note') / 20 * 100, 1) : 0; // Moyenne en pourcentage
                return [
                    'id' => $formation->id,
                    'name' => $formation->titre,
                    'apprenants' => $formation->apprenants->count(),
                    'moyenne' => $moyenne,
                ];
            });

        // Total des formations
        $totalFormations = $formations->count();

        // Total des apprenants uniques
        $totalApprenants = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })
            ->with('apprenants')
            ->get()
            ->flatMap(fn($formation) => $formation->apprenants->pluck('id'))
            ->unique()
            ->count();

        // Activités récentes (exemple basé sur les notes ajoutées récemment)
        $recentActivities = Note::whereIn('formation_id', $formations->pluck('id'))
            ->with(['apprenant', 'formation', 'discipline'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'type' => 'Note ajoutée',
                    'description' => "{$note->apprenant->prenom} {$note->apprenant->nom} - {$note->discipline->nom}: {$note->note}/20",
                    'date' => $note->updated_at->format('Y-m-d H:i'),
                ];
            });

        return inertia('Formateurs/Dashboard', [
            'dashboardData' => [
                'totalFormations' => $totalFormations,
                'totalApprenants' => $totalApprenants,
                'recentActivities' => $recentActivities,
                'formations' => $formations,
            ],
        ]);
    }
}
