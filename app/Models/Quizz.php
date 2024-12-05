<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizz extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'project_id',
        'domainType',
        'publish',
        'design_id',
        'meta_title',
        'meta_description',
        'meta_favicon',
        'meta_image',
        'next_question_text',
        'next_to_form',
    ];
}
