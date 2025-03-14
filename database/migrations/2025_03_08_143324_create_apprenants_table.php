<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Champs spécifiques à l'étudiant
            $table->string('nom'); // Nom de l'étudiant
            $table->string('prenom'); // Prénom de l'étudiant
            $table->date('date_naissance'); // Date de naissance
            $table->string('adresse'); // Adresse
            $table->string('telephone'); // Numéro de téléphone
            $table->unsignedBigInteger('niveau_id'); // Clé étrangère vers niveaux
            $table->foreignId('formation_id')->constrained()->onDelete('cascade'); // Clé étrangère vers formations

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
