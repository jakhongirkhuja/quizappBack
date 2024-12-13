<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\UserTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function paymentOrder(OrderRequest $request){
        $data = $request->validated();
        if(Order::where('created_at', '>', Carbon::now()->subSeconds(6))->where('user_id', Auth::id())->exists()) return;
        Log::info(json_encode($data));
        try {
            $order = new Order();
            $order->saveModel($data);
            $userTransaction = new UserTransaction();
            $userTransaction->user_id = $order->user_id;
            $userTransaction->price = $order->price;
            $userTransaction->sign= true;
            $userTransaction->service = 'payment';
            $userTransaction->service_id = $order->id;
            $userTransaction->action_user_id =  $order->user_id;
            $userTransaction->save();
            $input = 'm=66f6feb9cd20;ac.order_id='.$order->id.';a='.($order->price*100).';callback=null;callback_timeout=5000';
            $base64Encoded = base64_encode($input);
            $fullUrl = 'https://checkout.paycom.uz/' . $base64Encoded;
            return response()->json(['success'=>true, 'data'=>$order,'link'=>$fullUrl]);
        } catch (\Throwable $th) {
            Log::error(''.$th);
            return response()->json(['success'=>false, 'errors' => 'not given'], Response::HTTP_BAD_GATEWAY);
        }
    }
    public function transactions(){
        return response()->json(UserTransaction::where('user_id', Auth::id())->latest()->get());
    }
}
