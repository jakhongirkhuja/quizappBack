<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Templete extends Model
{
    protected $fillable = [
        'title', 'title_uz', 'category_id', 'domainType', 'publish',
        'meta_title', 'meta_title_uz', 'meta_description', 'meta_description_uz',
        'startPage','meta_favicon','meta_image','next_question_text','next_question_text_uz','next_to_form','next_to_form_uz'
    ];

    public function questions()
    {
        return $this->hasMany(TempleteQuestion::class);
    }
    public function startpage()
    {
        return $this->hasOne(TempleteStartPage::class);
    }
}
