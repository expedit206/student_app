<?php

namespace App\Http\Controllers\Apprenant;

use App\Models\Note;
use Inertia\Inertia;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    // Nouvelle méthode pour la page des notes
    public function dashboard(Request $request)
    {
        // Votre code existant pour le dashboard (déjà fonctionnel)
        $user = auth()->user();
        $apprenant = $user->apprenant;
        $apprenantId = $apprenant ? $apprenant->id : null;

        \Log::info('User ID: ' . $user->id . ', Apprenant: ' . ($apprenant ? json_encode($apprenant->toArray()) : 'null'));

        if (!$apprenant || !$apprenant->formation_id) {
            \Log::info('Aucune formation assignée à cet apprenant.');
            $formationsData = [];
            $totalFormations = 0;
            $totalNotes = 0;
            $moyenneGenerale = 0;
        } else {
            $formation = Formation::with(['notes' => function ($query) use ($apprenantId) {
                $query->where('apprenant_id', $apprenantId);
            }, 'formateurs'])
                ->find($apprenant->formation_id);

            if (!$formation) {
                \Log::info('Formation introuvable pour ID: ' . $apprenant->formation_id);
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

    public function notes(Request $request)
    {
        $user = auth()->user();
        $apprenant = $user->apprenant;

        if (!$apprenant || !$apprenant->formation_id) {
            return Inertia::render('Apprenants/Notes', [
                'notesData' => [
                    'formation' => null,
                    'notes' => [],
                    'totalNotes' => 0,
                    'moyenne' => 0,
                ],
            ]);
        }

        $apprenantId = $apprenant->id;
        $formation = $apprenant->formation;

        // Récupérer toutes les notes de l'apprenant pour sa formation
        $notes = Note::where('apprenant_id', $apprenantId)
            ->where('formation_id', $formation->id)
            ->with('discipline')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'discipline' => $note->discipline->nom, // Adaptez au champ réel
                    'note' => $note->note,
                    'max_note' => 20,
                    'date' => $note->created_at->format('Y-m-d'),
                    'commentaire' => $note->commentaire ?? 'Aucun commentaire',
                ];
            });

        $formationData = [
            'id' => $formation->id,
            'name' => $formation->titre, // Adaptez si 'name' au lieu de 'titre'
        ];

        return Inertia::render('Apprenants/Notes', [
            'notesData' => [
                'formation' => $formationData,
                'notes' => $notes,
                'totalNotes' => $notes->count(),
                'moyenne' => $notes->isNotEmpty() ? round($notes->avg('note') / 20 * 100, 1) : 0,
            ],
        ]);
    }
}
