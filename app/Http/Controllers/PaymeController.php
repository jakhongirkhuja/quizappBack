<?php

namespace App\Http\Controllers;

use App\Services\PaymeService;
use Illuminate\Http\Request;

class PaymeController extends Controller
{
    public function index(Request $request, PaymeService $paymeService){
        return $paymeService->index($request);
    }
}
