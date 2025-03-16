<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['etudiant_id', 'discipline_id', 'formation_id', 'note'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

}
