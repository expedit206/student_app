<?php

namespace App\Models;

use App\Models\Apprenant;
use App\Models\Formation;
use App\Models\Discipline;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['apprenant_id', 'discipline_id', 'formation_id', 'note'];
    // protected $with = 'formation';

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class);
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
