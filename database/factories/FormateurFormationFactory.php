<?php

namespace Database\Factories;

use App\Models\Formateur;
use App\Models\Formation;
use App\Models\FormateurFormation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormateurFormationFactory extends Factory
{
    protected $model = \App\Models\FormateurFormation::class; // Assurez-vous que ce modèle existe

    public function definition()
    {
        return [
            'formation_id' => \App\Models\Formation::factory(), // Crée une formation associée
            'formateur_id' => \App\Models\Formateur::factory(), // Crée un formateur associé
            // Ajoutez d'autres colonnes si nécessaire
        ];
    }
}
