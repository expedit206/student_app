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
        // Liste des formateurs à créer (manuellement)
        $formateurs = [
            [
                'email' => 'formateur@aaa',
                'nom' => 'special',
                'prenom' => 'Jean',
                'specialite' => 'Mathématiques',
                'telephone' => '0600000001',
                'dernier_diplome' => 'Master en Mathématiques',
            ],
            [
                'email' => 'formateur2@aaa.com',
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'specialite' => 'Physique',
                'telephone' => '0600000002',
                'dernier_diplome' => 'Doctorat en Physique',
            ],
            [
                'email' => 'formateur3@aaa.com',
                'nom' => 'Lefevre',
                'prenom' => 'Marc',
                'specialite' => 'Informatique',
                'telephone' => '0600000003',
                'dernier_diplome' => 'Master en Informatique',
            ],
            [
                'email' => 'formateur4@aaa.com',
                'nom' => 'Durand',
                'prenom' => 'Claire',
                'specialite' => 'Chimie',
                'telephone' => '0600000004',
                'dernier_diplome' => 'Doctorat en Chimie',
            ],
            [
                'email' => 'formateur5@aaa.com',
                'nom' => 'Moreau',
                'prenom' => 'Luc',
                'specialite' => 'Histoire',
                'telephone' => '0600000005',
                'dernier_diplome' => 'Master en Histoire',
            ],
            [
                'email' => 'formateur6@aaa.com',
                'nom' => 'Roux',
                'prenom' => 'Émilie',
                'specialite' => 'Littérature',
                'telephone' => '0600000006',
                'dernier_diplome' => 'Doctorat en Littérature',
            ],
            [
                'email' => 'formateur7@aaa.com',
                'nom' => 'Petit',
                'prenom' => 'Thomas',
                'specialite' => 'Biologie',
                'telephone' => '0600000007',
                'dernier_diplome' => 'Master en Biologie',
            ],
            [
                'email' => 'formateur8@aaa.com',
                'nom' => 'Girard',
                'prenom' => 'Julie',
                'specialite' => 'Économie',
                'telephone' => '0600000008',
                'dernier_diplome' => 'Master en Économie',
            ],
            [
                'email' => 'formateur9@aaa.com',
                'nom' => 'Lemoine',
                'prenom' => 'Paul',
                'specialite' => 'Philosophie',
                'telephone' => '0600000009',
                'dernier_diplome' => 'Doctorat en Philosophie',
            ],
            [
                'email' => 'formateur10@aaa.com',
                'nom' => 'Benoit',
                'prenom' => 'Alice',
                'specialite' => 'Langues Étrangères',
                'telephone' => '0600000010',
                'dernier_diplome' => 'Master en Langues',
            ],
            [
                'email' => 'formateur@aaa.com',
                'nom' => 'Formateur',
                'prenom' => 'Spécial',
                'specialite' => 'Spécialité Unique',
                'telephone' => '0600000000',
                'dernier_diplome' => 'Doctorat en Enseignement',
            ],
        ];

        // Créer chaque formateur avec un utilisateur associé
        foreach ($formateurs as $formateurData) {
            // Créer l'utilisateur
            $user = User::create([
                'email' => $formateurData['email'],
                'password' => Hash::make('formateur'), // Mot de passe haché (même mot de passe pour tous)
                'role' => 'formateur',
                'remember_token' => Str::random(10),
            ]);

            // Créer le formateur et l'associer à l'utilisateur
            Formateur::create([
                'user_id' => $user->id,
                'nom' => $formateurData['nom'],
                'prenom' => $formateurData['prenom'],
                'specialite' => $formateurData['specialite'],
                'telephone' => $formateurData['telephone'],
                'dernier_diplome' => $formateurData['dernier_diplome'],
            ]);
        }
    }
}
