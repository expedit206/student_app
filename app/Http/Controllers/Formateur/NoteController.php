<?php

namespace App\Http\Controllers\Formateur;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function showAddNoteForm($formationId, $disciplineId)
    {
        $formateurId = auth()->user()->id;

        $etudiants = Etudiant::whereHas('formations', function ($query) use ($formationId) {
            $query->where('id', $formationId);
        })->get();

        return inertia('Formateurs/Notes/Add', [
            'etudiants' => $etudiants,
            'formationId' => $formationId,
            'disciplineId' => $disciplineId,
        ]);
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'formation_id' => 'required|exists:formations,id',
            'note' => 'required|numeric|min:0|max:20',
        ]);

        Note::create([
            'etudiant_id' => $request->etudiant_id,
            'discipline_id' => $request->discipline_id,
            'formation_id' => $request->formation_id,
            'note' => $request->note,
        ]);

        return redirect()->route('notes.index')->with('success', 'Note ajoutée avec succès.');
    }

}
