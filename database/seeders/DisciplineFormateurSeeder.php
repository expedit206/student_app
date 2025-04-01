<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DisciplineFormateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DisciplineFormateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DisciplineFormateur::factory()->count(5)->create();
    }
}
