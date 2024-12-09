<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomCreatePostAnswerRequest;
use App\Http\Requests\CustomCreatePostQuestionRequest;
use App\Http\Requests\CustomQuizFormPageImageRequest;
use App\Http\Requests\CustomQuizFormPageTextRequest;
use App\Http\Requests\CustomQuizStartPageImageRequest;
use App\Http\Requests\CustomQuizStartPageTextRequest;
use App\Models\Project;
use App\Models\Quizz;
use App\Services\CustomQuizzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CustomController extends Controller
{
    private $customQuizzService;
    public function __construct(CustomQuizzService $customQuizzService) {
        $this->customQuizzService = $customQuizzService;
    }
    public function quizzCreate($project_id){
        $quiz = new Quizz();
        $quiz->front_id  = request()->front_id;
        $quiz->uuid =Str::orderedUuid();
        $quiz->user_id =Auth::id();
        $quiz->project_id = $project_id;
        $quiz->save();
        return $quiz;
    }
    public function createQuizStartPageImages(CustomQuizStartPageImageRequest $request, $uuid ){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            $this->customQuizzService->uploadImageStartPage($request->validated(),$quizz);
            return response()->json($quizz,201);
        }
        return response()->json([],404);
    }
    public function createQuizFormPageImages(CustomQuizFormPageImageRequest $request, $uuid)  {
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            $this->customQuizzService->uploadImageFormPage($request->validated(),$quizz);
            return response()->json($quizz,201);
        }
        return response()->json([],404);
    }
    public function createQuizStartPageText(CustomQuizStartPageTextRequest $request, $uuid) {
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createQuizStartPageText($request->validated(),$quizz);
        }
        return response()->json([],404);
        
    }
    public function createQuizFormPageText(CustomQuizFormPageTextRequest $request, $uuid) {
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createQuizFormPageText($request->validated(),$quizz);
        }
        return response()->json([],404);
        
    }
    public function createPostQuestions(CustomCreatePostQuestionRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createPostQuestions($request->validated(),$quizz);
        }
        return response()->json([],404);
    }
    public function createPostAnswers(CustomCreatePostAnswerRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_id){
            $quizz = Quizz::where('front_id',request()->front_id)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createPostAnswers($request->validated(),$quizz);
        }
        return response()->json([],404);
    }
}
