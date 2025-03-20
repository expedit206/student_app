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
          
        
        ];
    }
}
