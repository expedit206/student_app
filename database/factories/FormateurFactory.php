<?php

namespace Database\Factories;

use App\Models\Formateur;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormateurFactory extends Factory
{
    protected $model = Formateur::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Crée un utilisateur associé
            'nom' => $this->faker->lastName, // Génère un nom aléatoire
            'prenom' => $this->faker->firstName, // Génère un prénom aléatoire
            'specialite' => $this->faker->word, // Génère une spécialité aléatoire
            'telephone' => $this->faker->phoneNumber, // Génère un numéro de téléphone aléatoire
            'dernier_diplome' => $this->faker->sentence(3), // Génère un diplôme aléatoire
        ];
    }
}
