<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleteAnswer extends Model
{
    protected $fillable = [
        'templete_questions_id', 'text', 'text_uz', 'image', 'file', 'rank','secondary_text','secondary_text_uz','time_select','rank_text_min','rank_text_min_uz','rank_text_max','rank_text_max_uz'
    ];

    public function question()
    {
        return $this->belongsTo(TempleteQuestion::class, 'templete_questions_id');
    }
}
