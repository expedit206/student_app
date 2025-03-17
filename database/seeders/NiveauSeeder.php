<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Niveau;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveaux = [
            ['titre' => 'Sans Diplôme'],
            ['titre' => 'Brevet des Collèges'],
            ['titre' => 'Baccalauréat'],
            ['titre' => 'BTS (Brevet de Technicien Supérieur)'],
            ['titre' => 'Licence'],
            ['titre' => 'Master'],
            ['titre' => 'Doctorat'],
            ['titre' => 'DUT (Diplôme Universitaire de Technologie)'],
            ['titre' => 'CAP (Certificat d’Aptitude Professionnelle)'],
            ['titre' => 'CQPM (Certificat de Qualification Professionnelle)'],
        ];

        foreach ($niveaux as $niveau) {
            Niveau::create($niveau);
        }
    }
}