<?php

namespace App\Models;

use App\Models\User;
use App\Models\Niveau;
use App\Models\Formation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apprenant extends Model
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
        'niveau_id', // Clé étrangère vers le niveau
        'formation_id', // Clé étrangère vers le niveau
    ];

    // Définir la relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Si un étudiant a plusieurs cours :
    // public function courses()
    // {
    //     return $this->hasMany(Course::class);
    // }


    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
   
}
