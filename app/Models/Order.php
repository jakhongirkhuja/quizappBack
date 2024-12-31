<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    public function saveModel($data){
        $this->user_id = Auth::id();
        $this->tarif_id =$data['tarif_id'];
        $this->halfyear = $data['halfyear'];
        $tarif = Tarif::find($data['tarif_id']);
        $price=  $tarif->price;
        $newPrice = $price;
        $leads =$tarif->leads;
        $newLeads =  $leads;
        if($data['halfyear']==1){
            $newPrice = (int) (($price * 6)*0.85);
            $newLeads = $leads * 6;
        }
        $this->leads =  $newLeads;
        $this->price = $newPrice;
        $this->save();
    }
}
