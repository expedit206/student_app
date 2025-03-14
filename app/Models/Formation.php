<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory; // Ajout du trait HasFactory

    protected $with = ['formateur']; // Charge automatiquement le formateur

    protected $fillable = [
        'titre',
        'description',
        'formateur_id',
        'nbh_hebdomadaire',
        'nbh_hebdomadaire',
        'nbh_total',
    ];

    // Relation avec le formateur
    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    // Relation avec les disciplines
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'discipline_formation'); // Relation many-to-many
    }
}
