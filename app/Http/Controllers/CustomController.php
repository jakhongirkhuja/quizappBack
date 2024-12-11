<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomCreatePostAnswerRequest;
use App\Http\Requests\CustomCreatePostQuestionRequest;
use App\Http\Requests\CustomQuizFormPageImageRequest;
use App\Http\Requests\CustomQuizFormPageTextRequest;
use App\Http\Requests\CustomQuizMetasRequest;
use App\Http\Requests\CustomQuizStartPageImageRequest;
use App\Http\Requests\CustomQuizStartPageTextRequest;
use App\Http\Requests\CustomQuizTitleRequest;
use App\Http\Requests\RemoveQuestionRequest;
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
    public function getQuizz($uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            if(request()->page=='formPage'){
                $quizz = Quizz::with('formPage')->where('front_id',request()->front_idP)->first();
            }elseif(request()->page=='questions'){
                
                $quizz = Quizz::with('questions.answers')->where('front_id',request()->front_idP)->first();
            }else{
                $quizz = Quizz::with('startPage')->where('front_id',request()->front_idP)->first();
            }
            return response()->json($quizz);
        }
        return response()->json([],404);
    }
    public function quizzCreate($project_id){
        $quiz = new Quizz();
        $quiz->front_id  = request()->front_idP;
        $quiz->uuid =Str::orderedUuid();
        $quiz->user_id =Auth::id();
        $quiz->project_id = $project_id;
        $quiz->save();
        return $quiz;
    }
    public function createMetas(CustomQuizMetasRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            $this->customQuizzService->createMetas($request->validated(),$quizz);
            return response()->json($quizz,201);
        }
        return response()->json([],404);
    }
    public function createPostTitle(CustomQuizTitleRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if($quizz){
               $data = $request->validated();
               $quizz->title = $data['title'];
               $quizz->save();
               return response()->json($quizz,201);
            }
            
        }
        return response()->json([],404);
    }
    public function createQuizStartPageImages(CustomQuizStartPageImageRequest $request, $uuid ){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
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
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
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
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createQuizStartPageText($request->validated(),$quizz);
        }
        return response()->json([],404);
        
    }
    public function createQuizFormPageText(CustomQuizFormPageTextRequest $request, $uuid) {
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createQuizFormPageText($request->validated(),$quizz);
        }
        return response()->json([],404);
        
    }
    public function createPostQuestions(CustomCreatePostQuestionRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createPostQuestions($request->validated(),$quizz);
        }
        return response()->json([],404);
    }
    public function createPostAnswers(CustomCreatePostAnswerRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createPostAnswers($request->validated(),$quizz);
        }
        return response()->json([],404);
    }
    public function removeQuestion(RemoveQuestionRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            return $this->customQuizzService->removeQuestion($request->validated());
        }
        return response()->json([],404);
    }
    public function duplicateQuestions(RemoveQuestionRequest $request,  $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            return $this->customQuizzService->duplicateQuestions($request->validated());
        }
        return response()->json([],404);
    }
}
