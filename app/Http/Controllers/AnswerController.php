<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Services\AnswerService;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    protected $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    public function index(): JsonResponse
    {
        $answers = $this->answerService->getAllAnswers();
        return response()->json($answers);
    }

    public function show($id): JsonResponse
    {
        $answer = $this->answerService->getAnswerById($id);
        return response()->json($answer);
    }

    public function store(AnswerRequest $request): JsonResponse
    {
        $answer = $this->answerService->createAnswer($request->validated());
        return response()->json($answer, 201);
    }

    public function update(AnswerRequest $request, $id): JsonResponse
    {
        $answer = $this->answerService->updateAnswer($id, $request->validated());
        return response()->json($answer);
    }

    public function destroy($id): JsonResponse
    {
        $this->answerService->deleteAnswer($id);
        return response()->json(['message' => 'Answer deleted successfully']);
    }
}
