<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPage extends Model
{
    protected function casts(): array
    {
        return [
            'name' => 'boolean',
            'name_required'=>'boolean',
            'phone' => 'boolean',
            'phone_required'=>'boolean',
            'email' => 'boolean',
            'email_required'=>'boolean',
            
        ];
    }
}
