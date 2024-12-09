<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizzController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']); 
Route::post('/register', [AuthController::class, 'register']); 
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'v1'], function () {
        
        Route::group(['prefix' => 'custom'], function () {

            Route::group(['prefix' => 'project'], function () {

                Route::post('{uuid}/quizz/startPageImages', [CustomController::class, 'createQuizStartPageImages']);
                Route::post('{uuid}/quizz/startPageText', [CustomController::class, 'createQuizStartPageText']);
                Route::post('{uuid}/quizz/formPageImages', [CustomController::class, 'createQuizFormPageImages']);
                Route::post('{uuid}/quizz/formPageText', [CustomController::class, 'createQuizFormPageText']);

                Route::post('{uuid}/quizz/postQuestions', [CustomController::class, 'createPostQuestions']);
                Route::post('{uuid}/quizz/postAnswers', [CustomController::class, 'createPostAnswers']);
            });
        });
        
         
    });
    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::get('/{id}', [ProjectController::class, 'show']); 
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
