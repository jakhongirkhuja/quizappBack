<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTarifRequest;
use App\Services\TarifService;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    protected $tarifService;

    public function __construct(TarifService $tarifService)
    {
        $this->tarifService = $tarifService;
    }

    public function index()
    {
      
        return $this->tarifService->getAllTarif();
    }
    public function store(PostTarifRequest $request)
    {
        return $this->tarifService->createTarif($request->validated());
    }


    public function destroy($id)
    {
        $this->tarifService->deleteTarif($id);
        return response()->json(['message' => 'Quizz deleted successfully'],204);
    }
}
