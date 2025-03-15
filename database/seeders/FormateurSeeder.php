<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Formateur;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FormateurSeeder extends Seeder
{
    public function run()
    {
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
        // Crée 10 formateurs aléatoires
        Formateur::factory()->count(10)->create();
    }
}
