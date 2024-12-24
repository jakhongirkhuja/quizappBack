<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
   
    public function quizz()
    {
        return $this->belongsTo(Quizz::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
}
