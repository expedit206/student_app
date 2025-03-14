<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'user_id', // Ajoutez user_id pour la relation
        'nom',
        'prenom',
        'date_naissance',
        'adresse',
        'telephone',
        '', // Si applicable
        'niveau_id', // Clé étrangère vers le niveau
    ];

    // Définir la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Si un étudiant a plusieurs cours :
    // public function courses()
    // {
    //     return $this->hasMany(Course::class);
    // }

   
}
