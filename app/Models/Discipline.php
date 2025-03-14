<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    // Définissez les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'nom_discipline',
        'description',
    ];

    // Si vous avez besoin de définir des relations, vous pouvez le faire ici
    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'discipline_formation');
    }
}
