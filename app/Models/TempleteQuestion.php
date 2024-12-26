<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleteQuestion extends Model
{
    protected $fillable = ['templete_id', 'type', 'question', 'question_uz', 'image'];

    public function templete()
    {
        return $this->belongsTo(Templete::class);
    }

    public function answers()
    {
        return $this->hasMany(TempleteAnswer::class, 'templete_questions_id');
    }
}
