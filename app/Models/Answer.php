<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected function casts(): array
    {
        return [
            'front_id' => 'integer',
            'custom_answer'=>'boolean',
            'selected'=>'boolean',
            
        ];
    }
}
