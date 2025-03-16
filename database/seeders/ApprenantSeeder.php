<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Apprenant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApprenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            'niveau_id' => rand(1, 5), // Supposons que vous ayez des niveaux de 1 à 5
            'formation_id' => rand(1, 5), // Ajoutez une clé étrangère vers les formations
        ]);

        // Créez des utilisateurs et des étudiants
        for ($i = 1; $i <= 50; $i++) {
            // Créez un utilisateur
            $user = User::create([
                'email' => 'Apprenant' . $i . '@example.com',
                'password' => Hash::make('password'), // Mot de passe par défaut
                'role' => 'apprenant',
            ]);

            // Créez un étudiant lié à cet utilisateur
            Apprenant::create([
                'user_id' => $user->id, // Clé étrangère
                'nom' => 'Nom' . $i,
                'prenom' => 'Prénom' . $i,
                'date_naissance' => now()->subYears(rand(18, 25)), // Âge aléatoire
                'adresse' => 'Adresse ' . $i,
                'telephone' => '06000000' . $i, // Numéro de téléphone fictif
                'niveau_id' => rand(1, 5), // Supposons que vous ayez des niveaux de 1 à 5
                'formation_id' => rand(1, 5), // Ajoutez une clé étrangère vers les formations
            ]);
        }
    }
}
