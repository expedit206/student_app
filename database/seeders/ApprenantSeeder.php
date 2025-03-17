<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Apprenant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ApprenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create(); // Initialiser Faker

        // Créez un utilisateur spécial
        $specialApprenant = User::create([
            'email' => 'apprenant@aaa', // Assurez-vous que l'email est valide
            'password' => Hash::make('apprenant'), // Mot de passe par défaut
            'remember_token' => Str::random(10),
            'role' => 'apprenant', // Rôle d'étudiant
        ]);

        // Créez un enregistrement pour cet étudiant dans la table Apprenants
        Apprenant::create([
            'user_id' => $specialApprenant->id, // Clé étrangère
            'nom' => 'Admin',
            'prenom' => 'Admin',
            'date_naissance' => now()->subYears(rand(18, 25)), // Âge aléatoire
            'adresse' => 'Adresse spéciale',
            'telephone' => '0600000000', // Numéro de téléphone fictif
            'niveau_id' => rand(1, 10), // Supposons que vous ayez des niveaux de 1 à 10
            'formation_id' => rand(1, 10), // Ajoutez une clé étrangère vers les formations
        ]);

        // Créez des utilisateurs et des étudiants
        for ($i = 1; $i <= 50; $i++) {
            // Créez un utilisateur
            $user = User::create([
                'email' => 'apprenant' . $i . '@example.com',
                'password' => Hash::make('password'), // Mot de passe par défaut
                'role' => 'apprenant',
            ]);

            // Créez un étudiant lié à cet utilisateur
            Apprenant::create([
                'user_id' => $user->id, // Clé étrangère
                'nom' => $faker->lastName, // Utiliser Faker pour un nom aléatoire
                'prenom' => $faker->firstName, // Utiliser Faker pour un prénom aléatoire
                'date_naissance' => $faker->dateTimeBetween('-25 years', '-18 years'), // Âge aléatoire
                'adresse' => $faker->address, // Utiliser Faker pour une adresse
                'telephone' => '06000000' . $i, // Numéro de téléphone fictif
                'niveau_id' => rand(1, 10), // Supposons que vous ayez des niveaux de 1 à 10
                'formation_id' => rand(1, 10), // Ajoutez une clé étrangère vers les formations
            ]);
        }
    }
}
