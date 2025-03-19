<?php

namespace App\Http\Controllers\Apprenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Nouvelle méthode pour la page des notes
    public function notes(Request $request, $formationId)
    {
        $apprenant = auth()->user();
        $apprenantId = $apprenant->id;

        // Vérifier que la formation appartient à l'apprenant
        if ($apprenant->formation_id != $formationId) {
            abort(403, 'Vous n\'êtes pas autorisé à voir les notes de cette formation.');
        }

        // Récupérer les notes de l'apprenant pour cette formation
        $notes = Note::where('apprenant_id', $apprenantId)
            ->where('formation_id', $formationId)
            ->with(['discipline'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'discipline' => $note->discipline->nom, // Adaptez au champ réel
                    'note' => $note->note,
                    'max_note' => 20, // Supposant une note sur 20
                    'date' => $note->created_at->format('Y-m-d'),
                    'commentaire' => $note->commentaire ?? 'Aucun commentaire', // Champ optionnel
                ];
            });

        // Informations sur la formation
        $formation = Formation::findOrFail($formationId);
        $formationData = [
            'id' => $formation->id,
            'name' => $formation->titre, // Adaptez au champ réel
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
