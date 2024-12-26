<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleteStartPage extends Model
{
    protected $fillable = [
        'templete_id',
        'hero_image',
        'hero_image_mobi',
        'logo',
        'slogan_text',
        'slogan_text_uz',
        'title',
        'title_uz',
        'title_secondary',
        'title_secondary_uz',
        'button_text',
        'button_text_uz',
        'phoneNumber',
        'phoneNumber_uz',
        'companyName_text',
        'companyName_text_uz',
        'design_type',
        'design_alignment',
    ];
}
