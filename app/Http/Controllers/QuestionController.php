<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index(): JsonResponse
    {
        $questions = $this->questionService->getAllQuestions();
        return response()->json($questions);
    }

    public function show($id): JsonResponse
    {
        $question = $this->questionService->getQuestionById($id);
        return response()->json($question);
    }

    public function store(QuestionRequest $request): JsonResponse
    {
        $question = $this->questionService->createQuestion($request->validated());
        return response()->json($question, 201);
    }

    public function update(QuestionRequest $request, $id): JsonResponse
    {
        $question = $this->questionService->updateQuestion($id, $request->validated());
        return response()->json($question);
    }

    public function destroy($id): JsonResponse
    {
        $this->questionService->deleteQuestion($id);
        return response()->json(['message' => 'Question deleted successfully']);
    }
}
