<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ApprenantSeeder;
use Database\Seeders\FormateurSeeder;
use Database\Seeders\FormationSeeder;
use Database\Seeders\DisciplineSeeder;
use Database\Seeders\NiveauSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\FormateurFormationSeeder;
use Database\Seeders\DisciplineFormateurSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            FormateurSeeder::class,
            DisciplineSeeder::class,
            FormationSeeder::class,
            FormateurFormationSeeder::class,

            // UsersTableSeeder::class,

            NiveauSeeder::class,
            ApprenantSeeder::class,
            DisciplineFormateurSeeder::class,


        ]);

     
         User::create([
            'email' => 'admin@aaa',
            'password' => Hash::make('admin'), // Mot de passe haché
            'role' => 'admin', // Rôle de formateur
            'remember_token' => Str::random(10),
        ]);
    }
}
