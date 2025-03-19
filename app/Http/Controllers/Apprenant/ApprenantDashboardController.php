<?php

namespace App\Http\Controllers\Apprenant;

use App\Models\Note;
use Inertia\Inertia;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprenantDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $apprenant = auth()->user(); // Apprenant connecté
        $apprenantId = $apprenant->id;

        // Débogage : Vérifier l'ID de la formation
        \Log::info('Apprenant ID: ' . $apprenantId . ', Formation ID: ' . $apprenant->formation_id);

        // Récupérer la formation de l'apprenant (via formation_id)
        $formation = Formation::with(['notes' => function ($query) use ($apprenantId) {
            $query->where('apprenant_id', $apprenantId);
        }, 'formateurs'])
            ->find($apprenant->formation_id);
        dd($formation);
// icicicicicici selection de la formation erroné
        // Débogage : Vérifier si la formation est trouvée
        if (!$formation) {
            \Log::info('Aucune formation trouvée pour cet apprenant.');
            $formationsData = [];
            $totalFormations = 0;
            $totalNotes = 0;
            $moyenneGenerale = 0;
        } else {
            \Log::info('Formation trouvée: ' . json_encode($formation->toArray()));
            $notes = $formation->notes;
            $moyenne = $notes->isNotEmpty() ? round($notes->avg('note') / 20 * 100, 1) : 0;
            $formateur = $formation->formateurs->first();
            $formationsData = [
                [
                    'id' => $formation->id,
                    'name' => $formation->titre, // Vérifiez que 'titre' est le bon champ
                    'formateur' => $formateur ? $formateur->nom : 'Non assigné',
                    'moyenne' => $moyenne,
                    'notes_count' => $notes->count(),
                ],
            ];

            // Statistiques
            $totalFormations = 1; // Une seule formation par apprenant
            $totalNotes = $notes->count();
            $moyenneGenerale = $moyenne;
        }

        // Activités récentes (dernières notes)
        $recentActivities = Note::where('apprenant_id', $apprenantId)
            ->with(['formation', 'discipline'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'type' => 'Note reçue',
                    'description' => "{$note->formation->titre} - {$note->discipline->nom}: {$note->note}/20",
                    'date' => $note->updated_at->format('Y-m-d H:i'),
                ];
            });

        // Débogage : Vérifier les données envoyées
        \Log::info('Données envoyées au frontend: ' . json_encode([
            'totalFormations' => $totalFormations,
            'totalNotes' => $totalNotes,
            'moyenneGenerale' => $moyenneGenerale,
            'recentActivities' => $recentActivities->toArray(),
            'formations' => $formationsData,
        ]));

        return Inertia::render('Apprenants/Dashboard', [
            'dashboardData' => [
                'totalFormations' => $totalFormations,
                'totalNotes' => $totalNotes,
                'moyenneGenerale' => $moyenneGenerale,
                'recentActivities' => $recentActivities,
                'formations' => $formationsData,
            ],
        ]);
    }
}
