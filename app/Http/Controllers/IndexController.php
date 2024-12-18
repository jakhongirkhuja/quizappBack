<?php

namespace App\Http\Controllers;

use App\Models\Quizz;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function qa($uud){
        $quizz = Quizz::with('formPage','startPage','design','questions.answers')->where('url',$uud)->first();
        if($quizz){
            return response()->json($quizz);
        } 
        return response()->json(null,404);
    }
}
