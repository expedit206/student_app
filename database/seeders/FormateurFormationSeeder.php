<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormateurFormation;

class FormateurFormationSeeder extends Seeder
{
    public function run()
    {
        // Crée 10 associations aléatoires entre formateurs et formations
        FormateurFormation::factory()->count(100)->create();
    }
}
