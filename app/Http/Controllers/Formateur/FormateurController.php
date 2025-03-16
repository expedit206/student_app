<?php

namespace App\Http\Controllers\Formateur;

use Inertia\Inertia;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Discipline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Apprenant; // Assure-toi d'importer le modèle Apprenant

class FormateurController extends Controller
{
    public function mes_formations()
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirige vers la page de connexion si non authentifié
        }

        // Récupérer l'ID du formateur authentifié
        $formateurId = auth()->user()->id;

        // Récupérer les formations du formateur avec les relations pivot
        $formations = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateurs.id', $formateurId);
        })->get();

        // Retourner une réponse Inertia avec les données
        return Inertia::render('Formateurs/Formations/Index', [
            'formations' => $formations,
        ]);
    }

    public function showApprenants()
    {
       
        $formateurId = auth()->user()->id; // Récupérer l'ID du formateur authentifié

        // Récupérer les formations du formateur
        $formations = Formation::with('apprenants.user')
            ->whereHas('formateurs', function ($query) use ($formateurId) {
                $query->where('formateur_id', $formateurId);
            })
            ->get();

        // Récupérer tous les apprenants liés à ces formations
        $apprenants = $formations->flatMap(function ($formation) {
            return $formation->apprenants; // Récupérer les apprenants pour chaque formation
        });

        return inertia('Formateurs/Apprenants/Index', [
            'apprenants' => $apprenants,
            'formations' => $formations,
        ]);
 
    }
    public function showDisciplines()
    {
        $formateurId = auth()->user()->id; // Récupérer l'ID du formateur authentifié

        // Récupérer les disciplines associées au formateur
        $disciplines = Discipline::with('formations')
            ->whereHas('formateurs', function ($query) use ($formateurId) {
                $query->where('formateur_id', $formateurId);
            })
            ->get();

        // Récupérer les formations associées à ce formateur
        $formations = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })->get();

        return inertia('Formateurs/Disciplines/Index', [
            'disciplines' => $disciplines,
            'formations' => $formations, // Passer les formations au composant
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
