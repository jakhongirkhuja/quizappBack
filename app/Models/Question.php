<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected function casts(): array
    {
        return [
            'front_id' => 'integer',
            'expanded'=>'boolean',
            'hidden'=>'boolean',
            'expanded_footer'=>'boolean',
            'multiple_answers'=>'boolean',
            'required'=>'boolean',
            'long_text'=>'boolean',
            'scroll'=>'boolean',
        ];
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
