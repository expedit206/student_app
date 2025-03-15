<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition()
    {
        return [
            'titre' => $this->faker->sentence(3), // Génère un titre de formation aléatoire
            'description' => $this->faker->paragraph(), // Génère une description aléatoire
            'nbh_hebdomadaire' => $this->faker->numberBetween(1, 10), // Nombre d'heures hebdomadaires aléatoires
            'nbh_total' => $this->faker->numberBetween(10, 100), // Nombre total d'heures aléatoires
        ];
    }
}
