<?php
namespace App\Services;

use App\Models\Project;
use App\Models\Quizz;
use App\Models\Tarif;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
class TarifService{
    public function getAllTarif(){
        return response()->json(Tarif::orderby('leads','asc')->get());
    }
    public function createTarif($data){
        $tarif = new Tarif();
        $tarif->leads = $data['leads'];
        $tarif->price = $data['price'];
        $tarif->save();
        return response()->json($tarif, 201);
    }
    public function deleteTarif($id){
        $tarif = Tarif::find($id);
        if($tarif){
            $tarif->delete();
            
        }
    }
}