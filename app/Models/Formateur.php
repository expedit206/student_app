<?php

namespace App\Models;

use App\Models\Apprenant;
use App\Models\Formation;
use App\Models\Discipline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formateur extends Model
{
    use HasFactory; // Ajout du trait HasFactory

    protected $with = [
        // 'formations',
    //  'disciplines'
]; // Chargement des relations
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'specialite',
        'telephone',
        'dernier_diplome',
        'administrateur_id', // Ajout de la clé étrangère si nécessaire
    ];
    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'formateur_formation', 'formateur_id', 'formation_id');
    }
  
    // Relation avec les disciplines via la table pivot
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'formateur_discipline', 'formateur_id', 'discipline_id');
    }
}
