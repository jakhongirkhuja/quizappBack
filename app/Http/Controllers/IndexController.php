<?php

namespace App\Http\Controllers;

use App\Models\Lead;
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
    public function submitForm(Request $request, $uud){
        $quizz = Quizz::where('url',$uud)->first();
        
        if($quizz){
            $leads = Lead::where('phone', $request->phone)->first();
            if($leads){
                return response()->json($leads,201);
            }
            $leads = new Lead();
            $leads->quizz_id = $quizz->id;
            $leads->project_id = $quizz->project_id;
            $leads->ip = $request->ip();
            $leads->user_agent = $request->header('User-Agent');
            $leads->name = $request->name;
            $leads->email = $request->email;
            $leads->phone = $request->phone;
            $leads->answers = $request->answers;
            $leads->save();
            return response()->json($leads,201);
        }
        return response()->json(null, 404);
    }
}
