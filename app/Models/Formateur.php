<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    use HasFactory; // Ajout du trait HasFactory

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'dernier_diplome',
        '', // Si nécessaire pour la relation avec Administrateurs
    ];

    // Relation avec les formations
    public function formations()
    {
        return $this->hasMany(Formation::class); // Un formateur peut dispenser plusieurs formations
    }
}
