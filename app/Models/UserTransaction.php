<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    
    public function order()
    {
        return $this->belongsTo(Order::class,'service_id', 'id');
    }
}
