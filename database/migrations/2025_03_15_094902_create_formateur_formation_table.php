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
        Schema::create('formateur_formation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')
                ->constrained()
                ->onDelete('cascade'); // Supprime les enregistrements si la formation est supprimée
            $table->foreignId('formateur_id')
                ->constrained()
                ->onDelete('cascade');
            $table->unique(['formateur_id', 'formation_id']); // Supprime les enregistrements si le formateur est supprimé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_formation');
    }
};
