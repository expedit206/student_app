<?php

namespace App\Models;

use App\Models\Note;
use App\Models\Apprenant;
use App\Models\Formateur;
use App\Models\Discipline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formation extends Model
{
    use HasFactory; // Ajout du trait HasFactory

    protected $with = [
        'formateurs',
        'notes'
    ];

    protected $fillable = [
        'titre',
        'description',       // Ajout du champ pour heures totales
        // Ajoutez d'autres champs nécessaires ici
    ];

    // Relation avec les formateurs via la table pivot
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class);
    }

    public function apprenants()
    {
        return $this->hasMany(Apprenant::class);
    }

    // Relation avec les disciplines via la table pivot
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
