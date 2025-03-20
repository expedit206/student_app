<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    // Définissez les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'nom',
        'description',
        'heures_hebdo',
        'heures_total',
    ];

    // Si vous avez besoin de définir des relations, vous pouvez le faire ici
    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'discipline_formation');
    }
    public function formateurs()
    {
        return $this->belongsToMany(Formateur::class, 'discipline_formateur');
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
