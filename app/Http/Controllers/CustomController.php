<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomCreatePostAnswerRequest;
use App\Http\Requests\CustomCreatePostQuestionRequest;
use App\Http\Requests\CustomProjectTitleCreateRequest;
use App\Http\Requests\CustomQuizDesignRequest;
use App\Http\Requests\CustomQuizFormPageImageRequest;
use App\Http\Requests\CustomQuizFormPageTextRequest;
use App\Http\Requests\CustomQuizInstallButtonRequest;
use App\Http\Requests\CustomQuizInstallRequest;
use App\Http\Requests\CustomQuizMetasRequest;
use App\Http\Requests\CustomQuizStartPageImageRequest;
use App\Http\Requests\CustomQuizStartPageTextRequest;
use App\Http\Requests\CustomQuizTitleRequest;
use App\Http\Requests\CustomStartPageStatusRequest;
use App\Http\Requests\RemoveQuestionRequest;
use App\Models\Design;
use App\Models\FormPage;
use App\Models\Project;
use App\Models\Quizz;
use App\Models\StartPage;
use App\Services\CustomQuizzService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\For_;

class CustomController extends Controller
{
    private $customQuizzService;
    public function __construct(CustomQuizzService $customQuizzService) {
        $this->customQuizzService = $customQuizzService;
    }
    public function getQuizz($uuid){
        $project = Project::where('uuid', $uuid)->where('user_id', Auth::id())->first();
        if($project && request()->front_idP){
            if(request()->page=='formPage'){
                $quizz = Quizz::with('formPage')->where('front_id',request()->front_idP)->first();
            }elseif(request()->page=='questions'){
                
                $quizz = Quizz::with('questions.answers')->where('front_id',request()->front_idP)->first();
            }elseif(request()->page=='design'){
                
                $quizz = Quizz::with('design')->where('front_id',request()->front_idP)->first();
            }
            else{
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
        $quiz->url = (string)Str::orderedUuid().(string)Carbon::now()->timestamp;
        $quiz->project_id = $project_id;
        $quiz->save();
        $newDesign = new Design();
        $newDesign->bgColor='#FFFFFF';
        $newDesign->buttonColor="#FBCBBC";
        $newDesign->buttonStyle=0;
        $newDesign->buttonTextColor="#00000";
        $newDesign->designTitle="Светлая";
        $newDesign->progressBarStyle=0;
        $newDesign->quizz_id=$quiz->id;
        $newDesign->textColor="#00000";
        $newDesign->save();
        $formPage = new FormPage();
        $formPage->quizz_id = $quiz->id;
        $formPage->save();
        $startPage = new StartPage();
        $startPage->quizz_id = $quiz->id;
        $startPage->save();
        return $quiz;
    }
    public function removeProject($uuid){
        $project = Project::with('quizzs.questions.answers')->where('uuid', $uuid)->where('user_id', Auth::id())->first();
        if($project){
            return $this->customQuizzService->removeProject($project);
        }
        return response()->json([],404);
    }
    public function removeQuiz($uuid){
        $project = Project::where('uuid', $uuid)->where('user_id', Auth::id())->first();
        if($project && request()->front_idP){
            $quizz = Quizz::with('questions.answers')->where('front_id',request()->front_idP)->first();
            if($quizz){
                $this->customQuizzService->removeQuizz($quizz);
            }
        }
        return response()->json([],404);
    }
    public function createProjectTitle(CustomProjectTitleCreateRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project){
            $data = $request->validated();
            $project->name = $data['title'];
            $project->save();
            return response()->json($project,201);
        }
        return response()->json([],404);
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
    public function createDesign(CustomQuizDesignRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        $design =null;
        if($project && request()->front_idP){
            $quizz = Quizz::with('design')->where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            
            return  $this->customQuizzService->createDesign($request->validated(), $quizz);
        }
        return response()->json([],404);
    }
    public function createInstall(CustomQuizInstallRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createInstall($request->validated(),$quizz);
            
        }
        return response()->json([],404);
    }
    public function createInstallButtons(CustomQuizInstallButtonRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            return $this->customQuizzService->createInstallButtons($request->validated(),$quizz);
            
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
    public function startPageStatusUpdate(CustomStartPageStatusRequest $request, $uuid){
        $project = Project::where('uuid', $uuid)->first();
        if($project && request()->front_idP){
            $quizz = Quizz::where('front_id',request()->front_idP)->first();
            if(!$quizz){
                $quizz = $this->quizzCreate($project->id);
            }
            $data = $request->validated();
            $quizz->startPage =filter_var($data['startPage'], FILTER_VALIDATE_BOOLEAN); 
            $quizz->save();
            return response()->json($quizz, 200);
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
