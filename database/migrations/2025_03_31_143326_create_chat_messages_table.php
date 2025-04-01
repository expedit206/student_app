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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_role', ['apprenant', 'formateur', 'admin']);
            $table->unsignedBigInteger('receiver_id');
            $table->enum('receiver_role', ['apprenant', 'formateur', 'admin']);
            $table->text('message');
            $table->timestamp('timestamp');
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['sender_id', 'sender_role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
