<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\UserLead;
use App\Models\UserTransaction;
use Carbon\Carbon;

class OrderService{
    public function saveOrder($transaction){
        $completed_order = Order::where('id', $transaction->order_id)->first();
        $completed_order->status = 'payed';
        $completed_order->save();
        Log::info('Order status updated', [
            'order_id' => $completed_order->id,
            'status' => $completed_order->status
        ]);
        $userTransaction = new UserTransaction();
        $userTransaction->user_id = $completed_order->user_id;
        $userTransaction->amount = $completed_order->price;
        $userTransaction->sign= true;
        $userTransaction->service = 'payment';
        $userTransaction->service_id = $completed_order->id;
        $userTransaction->action_user_id =  $completed_order->user_id;
        $userTransaction->save();
        Log::info('User transaction recorded', [
            'transaction_id' => $userTransaction->id,
            'user_id' => $userTransaction->user_id,
            'amount' => $userTransaction->amount,
            'service' => $userTransaction->service
        ]);
        $userLeads = new UserLead();
        $userLeads->user_id = $completed_order->user_id;
        $userLeads->order_id = $completed_order->id;
        $userLeads->leads_added = $completed_order->leads;
        $userLeads->valid_from = Carbon::now();
        $userLeads->valid_until = $completed_order->halfyear? Carbon::now()->addMonths(6) : Carbon::now()->addMonth();
        $userLeads->save();
        Log::info('User leads added', [
            'user_leads_id' => $userLeads->id,
            'user_id' => $userLeads->user_id,
            'leads_added' => $userLeads->leads_added,
            'valid_from' => $userLeads->valid_from,
            'valid_until' => $userLeads->valid_until
        ]);
    }
}