<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Quizz;
use App\Models\UserLead;
use App\Models\VisitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function qa($uud){
        $quizz = Quizz::with('formPage','startPage','design','questions.answers')->where('url',$uud)->first();
        if($quizz){
            return response()->json($quizz);
        } 
        return response()->json(null,404);
    }
    public function visitlog(Request $request,$uud){
        $quizz = Quizz::where('url',$uud)->first();
        if($quizz){
            $visitLog = VisitLog::select('id')->where('quizz_id', $quizz->id)->where('user_agent', $request->header('User-Agent'))->first();
            if(!$visitLog){
                $visitLog = new VisitLog();
                $visitLog->quizz_id = $quizz->id;
                $visitLog->ip_address = $request->ip();
                $visitLog->user_agent = $request->header('User-Agent');
                $visitLog->referer = $request->header('referer')?? 'empty';
                $visitLog->save();
            }
        }
        return response()->json();
    }
    public function submitForm(Request $request, $uud){
        $quizz = Quizz::where('url',$uud)->first();
        
        if($quizz){
            $leads = Lead::where('phone', $request->phone)->where('quizz_id',$quizz->id)->first();
            if($leads){
                return response()->json($leads,201);
            }

            $testLeads = UserLead::where('user_id', $quizz->user_id)
            ->where('test', true)
            ->where('valid_until', '>', Carbon::now())
            ->where('status',true)
            ->where('leads_used','<',5)
            ->first();
            if($testLeads){
                $testLeads->leads_used +=1;
                if($testLeads->leads_used == $testLeads->leads_added){
                    $testLeads->status = false;
                }
                $testLeads->save();
                return response()->json($leads,201);
            }else{
                $realLeads = UserLead::where('user_id', $quizz->user_id)
                ->where('test', false)
                ->where('valid_until', '>', Carbon::now())
                ->where('leads_added', '>', DB::raw('leads_used'))
                ->where('status',true)
                ->first();
                if($realLeads){
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

                    $realLeads->leads_used +=1;

                    if($realLeads->leads_used == $realLeads->leads_added){
                        $realLeads->status = false;
                    }
                    $realLeads->save();
                    return response()->json($leads,201);
                }else{
                    return response()->json(null, 404);
                }
            }
            
        }
        return response()->json(null, 404);
    }
}
