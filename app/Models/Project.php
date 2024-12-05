<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'user_id',
    ];
    
    public function quizzs()
    {
        return $this->hasMany(Quizz::class);
    }
}
