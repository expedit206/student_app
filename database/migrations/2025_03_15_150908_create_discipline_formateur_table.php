<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplineFormateurTable extends Migration
{
    public function up()
    {
        Schema::create('discipline_formateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained()->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->nullable()->constrained('formations')->onDelete('cascade');
            $table->unique(['discipline_id', 'formateur_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discipline_formateur');
    }
}
