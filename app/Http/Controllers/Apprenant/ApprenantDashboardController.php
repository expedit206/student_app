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
        $user = auth()->user();
        $apprenant = $user->apprenant;
        $apprenantId = $apprenant ? $apprenant->id : null;

        if (!$apprenant || !$apprenant->formation_id) {
            $formationsData = [];
            $totalFormations = 0;
            $totalNotes = 0;
            $moyenneGenerale = 0;
        } else {
            $formation = Formation::with(['notes' => function ($query) use ($apprenantId) {
                $query->where('apprenant_id', $apprenantId);
            }, 'formateurs'])->find($apprenant->formation_id);

            if (!$formation) {
                $formationsData = [];
                $totalFormations = 0;
                $totalNotes = 0;
                $moyenneGenerale = 0;
            } else {
                $notes = $formation->notes;
                $moyenne = $notes->isNotEmpty() ? round($notes->avg('note') / 20 * 100, 1) : 0;
                $formateur = $formation->formateurs->first();

                $formationsData = [
                    [
                        'id' => $formation->id,
                        'name' => $formation->titre,
                        'formateur' => $formateur ? $formateur->nom : 'Non assigné',
                        'moyenne' => $moyenne,
                        'notes_count' => $notes->count(),
                    ],
                ];

                $totalFormations = 1;
                $totalNotes = $notes->count();
                $moyenneGenerale = $moyenne;
            }
        }

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

        // Récupérer les notifications non lues
        $notifications = $user->notifications()
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->take(10) // Limite raisonnable
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Apprenants/Dashboard', [
            'dashboardData' => [
                'totalFormations' => $totalFormations,
                'totalNotes' => $totalNotes,
                'moyenneGenerale' => $moyenneGenerale,
                'recentActivities' => $recentActivities,
                'formations' => $formationsData,
                'notifications' => $notifications, // Ajout des notifications
            ],
        ]);
    }
}
