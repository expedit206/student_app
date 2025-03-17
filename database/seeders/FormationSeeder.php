<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formation;

class FormationSeeder extends Seeder
{
    public function run()
    {
        // Crée 10 formations aléatoires
        Formation::factory()->count(10)->create();
    }
}
