<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('formations')->insert([
            [
                'titre' => 'Formation PHP',
                'description' => 'Apprenez les bases du PHP.',
                'formateur_id' => 1, // Changez en fonction de l'ID de votre formate2r
                'discipline_id' => 1, // Changez en fonction de l'ID de votre discipline
                'nbh_hebdomadaire' => 3,
                'nbh_total' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation Laravel',
                'description' => 'Maîtrisez le framework Laravel.',
                'formateur_id' => 2,
                'discipline_id' => 1,
                'nbh_hebdomadaire' => 4,
                'nbh_total' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation JavaScript',
                'description' => 'Comprendre les bases de JavaScript.',
                'formateur_id' => 2,
                'discipline_id' => 2,
                'nbh_hebdomadaire' => 3,
                'nbh_total' => 24,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation Vue.js',
                'description' => 'Développez des applications avec Vue.js.',
                'formateur_id' => 2,
                'discipline_id' => 2,
                'nbh_hebdomadaire' => 3,
                'nbh_total' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation React',
                'description' => 'Initiation à React.js.',
                'formateur_id' => 2,
                'discipline_id' => 2,
                'nbh_hebdomadaire' => 5,
                'nbh_total' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation Python',
                'description' => 'Apprenez Python pour le développement web.',
                'formateur_id' => 2,
                'discipline_id' => 3,
                'nbh_hebdomadaire' => 4,
                'nbh_total' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation Symfony',
                'description' => 'Maîtriser le framework Symfony.',
                'formateur_id' => 2,
                'discipline_id' => 1,
                'nbh_hebdomadaire' => 4,
                'nbh_total' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation SQL',
                'description' => 'Introduction aux bases de données SQL.',
                'formateur_id' => 2,
                'discipline_id' => 4,
                'nbh_hebdomadaire' => 2,
                'nbh_total' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation HTML & CSS',
                'description' => 'Les fondamentaux du développement web.',
                'formateur_id' => 2,
                'discipline_id' => 2,
                'nbh_hebdomadaire' => 3,
                'nbh_total' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Formation DevOps',
                'description' => 'Introduction aux pratiques DevOps.',
                'formateur_id' => 2,
                'discipline_id' => 5,
                'nbh_hebdomadaire' => 3,
                'nbh_total' => 27,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
