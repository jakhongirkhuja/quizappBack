<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartPage extends Model
{
    protected function casts(): array
    {
        return [
            'button_text_uz' => 'string',
          
        ];
    }
}
