<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Lié à users
            $table->string('type'); // Ex. "new_note", "formation_update"
            $table->text('message'); // Message à afficher
            $table->boolean('read')->default(false); // Lu ou non
            $table->json('data')->nullable(); // Données supplémentaires (ex. note_id)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
