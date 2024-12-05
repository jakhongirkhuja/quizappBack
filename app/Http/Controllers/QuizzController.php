<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizzRequest;
use App\Services\QuizzService;
use Illuminate\Http\JsonResponse;

class QuizzController extends Controller
{
    protected $quizzService;

    public function __construct(QuizzService $quizzService)
    {
        $this->quizzService = $quizzService;
    }

    public function index(): JsonResponse
    {
        $quizzes = $this->quizzService->getAllQuizzes();
        return response()->json($quizzes);
    }

    public function show($id): JsonResponse
    {
        $quizz = $this->quizzService->getQuizzById($id);
        return response()->json($quizz);
    }

    public function store(QuizzRequest $request): JsonResponse
    {
        $quizz = $this->quizzService->createQuizz($request->validated());
        return response()->json($quizz, 201);
    }

    public function update(QuizzRequest $request, $id): JsonResponse
    {
        $quizz = $this->quizzService->updateQuizz($id, $request->validated());
        return response()->json($quizz);
    }

    public function destroy($id): JsonResponse
    {
        $this->quizzService->deleteQuizz($id);
        return response()->json(['message' => 'Quizz deleted successfully'],204);
    }
}
