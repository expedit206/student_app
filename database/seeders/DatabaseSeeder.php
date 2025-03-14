<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\StudentSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\FormateurSeeder;
use Database\Seeders\FormationSeeder;
use Database\Seeders\DisciplineSeeder;
use Database\Seeders\UsersTableSeeder;

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

            // UsersTableSeeder::class,
            StudentSeeder::class,

        ]);

     
         User::create([
            'email' => 'admin@aaa',
            'password' => Hash::make('admin'), // Mot de passe haché
            'role' => 'admin', // Rôle de formateur
            'remember_token' => Str::random(10),
        ]);
    }
}
