<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineFormateur extends Model
{
    use HasFactory;

    // Si vous n'avez pas de colonne 'id' dans la table pivot
    // protected $primaryKey = null;

    // Indiquez que ce modèle ne doit pas gérer les timestamps
    public $timestamps = false;

    protected $table = 'discipline_formateur'; // Spécifiez le nom de la table pivot
    // Spécifiez les relations, si nécessaire
    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
