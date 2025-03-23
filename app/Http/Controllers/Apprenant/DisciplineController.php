<?php

namespace App\Http\Controllers\Apprenant;

use Inertia\Inertia;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisciplineController extends Controller
{
    public function showDisciplines(Request $request)
    {
        $user = $request->user();
        $apprenant = Apprenant::where('user_id', $user->id)->firstOrFail();

        // Charger la formation de l'apprenant avec ses disciplines
        $formation = $apprenant->formation()
            ->with(['disciplines' => function ($query) use ($apprenant) {
                $query->select('disciplines.id', 'nom', 'description', 'heures_hebdo', 'heures_total')
                    ->with(['formateurs' => function ($q) use ($apprenant) {
                        $q->select('formateurs.id', 'nom')
                            ->wherePivot('formation_id', $apprenant->formation_id); // Utiliser l'ID de la formation de l'apprenant
                    }]);
            }])
            ->firstOrFail();
        $data['formation']['disciplines'] = $formation->disciplines->map(function ($discipline, ) use($formation) {
            return [
                'id' => $discipline->id,
                'titre' => $formation->titre,
                'nom' => $discipline->nom,
                'description' => $discipline->description,
                'heures_hebdo' => $discipline->heures_hebdo ?? 0,
                'heures_total' => $discipline->heures_total ?? 0,
                'coefficient' => ($discipline->heures_total ?? 0) / 10,
                'formateur' => $discipline->formateurs->first()->nom ?? 'Non attribué',
            ];
        })->all();

        return Inertia::render('Apprenants/Disciplines', $data);
    }
}