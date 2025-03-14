<?php

namespace App\Http\Controllers\Formateur;

use Inertia\Inertia;
use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apprenant; // Assure-toi d'importer le modèle Apprenant

class FormateurController extends Controller
{
    public function mes_formations()
    {
        // Récupérer les formations du formateur authentifié
        $formateurId = auth()->user()->id;
        $formations = Formation::where('formateur_id', $formateurId)->get();

        // Retourner une réponse Inertia avec les données
        return Inertia::render('Formateurs/Formations/Index', [
            'formations' => $formations,
        ]);
    }

    public function showApprenants($id)
    {
        // Récupérer les apprenants pour la formation spécifique
        $apprenants = Student::where('formation_id', $id)->get();

        // Retourner une réponse Inertia avec les données
        return Inertia::render('Formateurs/Apprenants/Index', [
            'apprenants' => $apprenants,
        ]);
    }

    public function attributionNotes(Request $request)
    {
        // Logique pour attribuer des notes aux apprenants
        $validatedData = $request->validate([
            'apprenant_id' => 'required|exists:apprenants,id',
            'note' => 'required|numeric|min:0|max:100',
        ]);

        // Attribuer la note à l'apprenant
        $apprenant = Apprenant::find($validatedData['apprenant_id']);
        $apprenant->note = $validatedData['note'];
        $apprenant->save();

        // Redirection ou réponse Inertia
        return redirect()->route('formateur.formations.index')->with('success', 'Note attribuée avec succès.');
    }
}
