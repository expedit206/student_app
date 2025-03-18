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

    public function showApprenants(Request $request, Formation $formation = null)
    {
        $formateurId = auth()->user()->id; // Récupérer l'ID du formateur authentifié

        // Récupérer toutes les formations du formateur
        $formations = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })->get(['id', 'titre']);

        // Récupérer tous les apprenants liés aux formations du formateur
        $apprenants = Formation::with('apprenants.user')
            ->whereHas('formateurs', function ($query) use ($formateurId) {
                $query->where('formateur_id', $formateurId);
            })
            ->get()
            ->flatMap(function ($formation) {
                return $formation->apprenants->map(function ($apprenant) use ($formation) {
                    return [
                        'id' => $apprenant->id,
                        'nom' => $apprenant->nom,
                        'prenom' => $apprenant->prenom,
                        'telephone' => $apprenant->telephone,
                        'formation_id' => $formation->id,
                        'user' => ['email' => $apprenant->user->email],
                    ];
                });
            })
            ->unique('id')
            ->values();

        return inertia('Formateurs/Apprenants/Index', [
            'apprenants' => $apprenants,
            'formations' => $formations,
            'selectedFormation' => $formation ? $formation->id : null, // Optionnel, pour l'état initial
        ]);
    }


    
    public function showDisciplines()
{
    $formateurId = auth()->user()->id; // Récupérer l'ID du formateur authentifié

    // Récupérer les disciplines associées au formateur avec les données du pivot
    $disciplines = Discipline::with(['formations'])
        ->whereHas('formateurs', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })
        ->get()
        ->map(function ($discipline) use ($formateurId) {
            // Récupérer les formation_id associés au formateur pour cette discipline
            $formationIds = $discipline->formateurs()
                ->where('formateur_id', $formateurId)
                ->pluck('discipline_formateur.formation_id')
                ->toArray();
            return [
                'id' => $discipline->id,
                'nom' => $discipline->nom,
                'description' => $discipline->description,
                'formation_ids' => $formationIds, // Liste des formation_id associés
            ];
        });

    // Récupérer les formations associées à ce formateur
    $formations = Formation::whereHas('formateurs', function ($query) use ($formateurId) {
        $query->where('formateur_id', $formateurId);
    })->get(['id', 'titre']); // Simplifié pour ne renvoyer que l'essentiel

    return inertia('Formateurs/Disciplines/Index', [
        'disciplines' => $disciplines,
        'formations' => $formations,
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
