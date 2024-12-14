<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
   
    public function quizz()
    {
        return $this->hasOne(Quizz::class);
    }
}
