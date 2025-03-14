<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Formateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FormateurSeeder extends Seeder
{
    public function run()
    {
        // Créer un formateur spécial
        $specialUser = User::create([
            'email' => 'formateur@aaa',
            'password' => Hash::make('formateur'), // Mot de passe haché
            'role' => 'formateur', // Rôle de formateur
            'remember_token' => Str::random(10),
        ]);

        Formateur::create([
            'user_id' => $specialUser->id,
            'nom' => 'Formateur',
            'prenom' => 'Spécial',
            'specialite' => 'Spécialité Unique',
            'telephone' => '0600000000', // Numéro de téléphone fictif
            'dernier_diplome' => 'Doctorat en Enseignement',
        ]);

        // Créer 10 formateurs
        for ($i = 1; $i <= 10; $i++) {
            // Créer un utilisateur
            $user = User::create([
                'email' => 'formateur' . $i . '@example.com',
                'password' => Hash::make('password'), // Mot de passe par défaut
                'role' => 'formateur', // Rôle de formateur
                'remember_token' => Str::random(10),
            ]);

            // Créer un formateur lié à cet utilisateur
            Formateur::create([
                'user_id' => $user->id,
                'nom' => 'Nom' . $i,
                'prenom' => 'Prénom' . $i,
                'specialite' => 'Spécialité ' . $i,
                'telephone' => '06000000' . $i, // Numéro de téléphone fictif
                'dernier_diplome' => 'Diplôme ' . $i,
            ]);
        }
    }
}
