<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormateurController extends Controller
{
    public function index()
    {
        // Récupérer les formations du formateur authentifié
        $formateurId = auth()->user()->id;
        $formations = Formation::where('formateur_id', $formateurId)->get();

        return view('formateur.formations.index', compact('formations'));
    }

    public function showApprenants($id)
    {
        // Récupérer les apprenants pour la formation spécifique
        $apprenants = Apprenant::where('formation_id', $id)->get();

        return view('formateur.apprenants.index', compact('apprenants'));
    }

    public function attributionNotes(Request $request)
    {
        // Logique pour attribuer des notes aux apprenants
        // Validation et traitement des données
        // ...
    }
}
