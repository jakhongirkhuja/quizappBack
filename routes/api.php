<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\TempleteController;
use App\Http\Middleware\PaymeMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/check', [AuthController::class, 'check']);

Route::post('/user/update', [AuthController::class, 'userUpdate'])->middleware('auth:sanctum'); 
Route::get('/user/tarif', [AuthController::class, 'tarif'])->middleware('auth:sanctum'); 
Route::post('/user/paymentOrder', [OrderController::class, 'paymentOrder'])->middleware('auth:sanctum'); 
Route::get('/user/transactions', [OrderController::class, 'transactions'])->middleware('auth:sanctum'); 


Route::get('/user/statistics', [OrderController::class, 'statistics'])->middleware('auth:sanctum');
Route::get('/user/quizzes', [OrderController::class, 'quizzes'])->middleware('auth:sanctum');
Route::get('/user/leads', [OrderController::class, 'leads'])->middleware('auth:sanctum');
Route::get('/user/leads/{id}/delete', [OrderController::class, 'leadsDelete'])->middleware('auth:sanctum'); 
Route::get('/user/leads/{id}/seen', [OrderController::class, 'leadsSeen'])->middleware('auth:sanctum'); 

Route::post('/login', [AuthController::class, 'login']); 
Route::post('/register', [AuthController::class, 'register']); 
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/qa/{uuid}', [IndexController::class, 'qa']); 
Route::post('/submitForm/{uuid}', [IndexController::class, 'submitForm']); 
Route::post('/visitlog/{uuid}', [IndexController::class, 'visitlog']); 


Route::get('/createTarif',[TempleteController::class, 'createTarifs']);
Route::get('/createTemplete',[TempleteController::class, 'createTemplete']);

Route::middleware('auth:sanctum')->group(function () {



    Route::group(['prefix' => 'v1'], function () {
        Route::get('templetes', [CustomController::class, 'templetes']);
        Route::group(['prefix' => 'custom'], function () {
            
            Route::group(['prefix' => 'project'], function () {

                Route::get('{uuid}/quizz', [CustomController::class, 'getQuizz']);
                

                
                Route::post('{uuid}/postTitle', [CustomController::class, 'createProjectTitle']);
                Route::post('{uuid}/remove', [CustomController::class, 'removeProject']);

                Route::post('{uuid}/quizz/createFromTemplete', [CustomController::class, 'createFromTemplete']);
                Route::post('{uuid}/quizz/remove', [CustomController::class, 'removeQuiz']);
                Route::post('{uuid}/quizz/metas', [CustomController::class, 'createMetas']);
                Route::post('{uuid}/quizz/design', [CustomController::class, 'createDesign']);
                Route::post('{uuid}/quizz/install', [CustomController::class, 'createInstall']);
                Route::post('{uuid}/quizz/installButtons', [CustomController::class, 'createInstallButtons']);

                
                Route::post('{uuid}/quizz/postTitle', [CustomController::class, 'createPostTitle']);
                Route::post('{uuid}/quizz/startPageImages', [CustomController::class, 'createQuizStartPageImages']);
                Route::post('{uuid}/quizz/startPageText', [CustomController::class, 'createQuizStartPageText']);
                Route::post('{uuid}/quizz/startPageStatus', [CustomController::class, 'startPageStatusUpdate']);
                Route::post('{uuid}/quizz/formPageImages', [CustomController::class, 'createQuizFormPageImages']);
                Route::post('{uuid}/quizz/formPageText', [CustomController::class, 'createQuizFormPageText']);



                Route::post('{uuid}/quizz/postQuestions', [CustomController::class, 'createPostQuestions']);
                Route::post('{uuid}/quizz/postAnswers', [CustomController::class, 'createPostAnswers']);
                Route::post('{uuid}/quizz/removeQuestions', [CustomController::class, 'removeQuestion']);
                Route::post('{uuid}/quizz/duplicateQuestions', [CustomController::class, 'duplicateQuestions']);
            });
        });
        
         
    });
    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::get('/{uuid}', [ProjectController::class, 'show']); 
        Route::post('/', [ProjectController::class, 'store']); 
        Route::post('/{id}', [ProjectController::class, 'update']);
        Route::delete('/{id}', [ProjectController::class, 'destroy']);
        
        
    });
    Route::group(['prefix' => 'quizzes'], function () {
        Route::get('/', [QuizzController::class, 'index']); 
        Route::get('/{id}', [QuizzController::class, 'show']); 
        Route::post('/', [QuizzController::class, 'store']); 
        Route::put('/{id}', [QuizzController::class, 'update']); // Update a quiz
        Route::delete('/{id}', [QuizzController::class, 'destroy']); // Delete a quiz
    });

    Route::group(['prefix' => 'tarif'], function () {
        Route::get('/', [TarifController::class, 'index']); 
        Route::post('/', [TarifController::class, 'store']); 
        Route::delete('/{id}', [TarifController::class, 'destroy']);
    });

});




Route::group(['prefix' => 'questions'], function () {
    Route::get('/', [QuestionController::class, 'index']);
    Route::get('/{id}', [QuestionController::class, 'show']);
    Route::post('/', [QuestionController::class, 'store']);
    Route::post('/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}', [QuestionController::class, 'destroy']);
});

Route::group(['prefix' => 'answers'], function () {
    Route::get('/', [AnswerController::class, 'index']);
    Route::get('/{id}', [AnswerController::class, 'show']);
    Route::post('/', [AnswerController::class, 'store']);
    Route::put('/{id}', [AnswerController::class, 'update']);
    Route::delete('/{id}', [AnswerController::class, 'destroy']);
});



Route::post('/payme', [PaymeController::class, 'index'])->middleware(PaymeMiddleware::class);