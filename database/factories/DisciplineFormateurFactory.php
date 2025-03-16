<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DisciplineFormateurFactory extends Factory
{
    protected $model = \App\Models\DisciplineFormateur::class;

    public function definition()
    {
        return [
            'formateur_id' => \App\Models\Formateur::factory(), // Crée un formateur
            'discipline_id' => \App\Models\Discipline::factory(), // Crée une discipline
        ];
    }
}
