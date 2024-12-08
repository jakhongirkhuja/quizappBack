<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\FormPage;
use App\Models\Project;
use App\Models\Question;
use App\Models\Quizz;
use App\Models\StartPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class CustomQuizzService{
    public function uploadImageStartPage($data, $quizz){

        if(isset($data['hero_image'])){
            $startPage = StartPage::where('quizz_id', $quizz->id)->first();
            if($startPage){
                $this->deleteOldImage($startPage->hero_image);
            }else{
                $startPage = new StartPage();
                $startPage->quizz_id = $quizz->id;
            }
            $startPage->hero_image = $this->moveImage($data['hero_image']);
            $startPage->save();
        }
        if(isset($data['hero_image_mobi'])){
            $startPage = StartPage::where('quizz_id', $quizz->id)->first();
            if($startPage){
                $this->deleteOldImage($quizz->hero_image_mobi);
            }else{
                $startPage = new StartPage();
                $startPage->quizz_id = $quizz->id;
            }
            $startPage->hero_image_mobi = $this->moveImage($data['hero_image_mobi']);
            $startPage->save();
        }
        if(isset($data['logo'])){
            $startPage = StartPage::where('quizz_id', $quizz->id)->first();
            if($startPage){
                $this->deleteOldImage($quizz->logo);
            }else{
                $startPage = new StartPage();
                $startPage->quizz_id = $quizz->id;
            }
            $startPage->logo = $this->moveImage($data['logo']);
            $startPage->save();
        }
       
       
    }
    public function uploadImageFormPage($data, $quizz){

        if(isset($data['hero_image'])){
            $startPage = FormPage::where('quizz_id', $quizz->id)->first();
            if($startPage){
                $this->deleteOldImage($startPage->hero_image);
            }else{
                $startPage = new FormPage();
                $startPage->quizz_id = $quizz->id;
            }
            $startPage->hero_image = $this->moveImage($data['hero_image']);
            $startPage->save();
        }
        if(isset($data['hero_image_mobi'])){
            $startPage = FormPage::where('quizz_id', $quizz->id)->first();
            if($startPage){
                $this->deleteOldImage($quizz->hero_image_mobi);
            }else{
                $startPage = new FormPage();
                $startPage->quizz_id = $quizz->id;
            }
            $startPage->hero_image_mobi = $this->moveImage($data['hero_image_mobi']);
            $startPage->save();
        }
        
       
    }
    public function createQuizStartPageText($data, $quizz){
        $startPage = StartPage::where('quizz_id', $quizz->id)->first();
        if(!$startPage){
            $startPage = new StartPage();
            $startPage->quizz_id = $quizz->id;
        }
        $startPage->slogan_text = $data['slogan_text'];
        $startPage->title = $data['title'];
        $startPage->title_secondary = $data['title_secondary'];
        $startPage->button_text = $data['button_text'];
        $startPage->phoneNumber = $data['phoneNumber'];
        $startPage->companyName_text = $data['companyName_text'];
        $startPage->design_type = $data['design_type'];
        $startPage->design_alignment = $data['design_alignment'];
        $startPage->save();
        return response()->json($startPage, 201);
    }
    public function createQuizFormPageText($data, $quizz){
        $startPage = FormPage::where('quizz_id', $quizz->id)->first();
        if(!$startPage){
            $startPage = new FormPage();
            $startPage->quizz_id = $quizz->id;
        }
       
        $startPage->title = $data['title'];
        $startPage->title_secondary = $data['title_secondary'];
        $startPage->button_text = $data['button_text'];
        $startPage->name = $data['name'];
        $startPage->email = $data['email'];
        $startPage->phone = $data['phone'];
        $startPage->name_required = $data['name_required'];
        $startPage->email_required = $data['email_required'];
        $startPage->phone_required = $data['phone_required'];
      
        $startPage->save();
        return response()->json($startPage, 201);
    }
    private function moveImage(UploadedFile $image)
    {
        $destinationPath = public_path('start_page'); // Define where to store the images
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension(); // Generate a unique name for the image
        $image->move($destinationPath, $imageName); // Move the image to the specified directory
        return 'start_page/' . $imageName; // Return the relative path to store in the database
    }
    private function moveImageAnswers(UploadedFile $image)
    {
        $destinationPath = public_path('answers'); // Define where to store the images
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension(); // Generate a unique name for the image
        $image->move($destinationPath, $imageName); // Move the image to the specified directory
        return 'answers/' . $imageName; // Return the relative path to store in the database
    }

    /**
     * Delete the old image from the storage.
     * 
     * @param string|null $imagePath
     */
    private function deleteOldImage($imagePath)
    {
        if ($imagePath) {
            $filePath = public_path($imagePath);
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the image file
            }
        }
    }
    public function createPostQuestions($data, $quizz){
        $question = Question::where('front_id',$data['front_id'])->first();
        if(!$question){
            $question= new Question();
            $question->front_id  =$data['front_id'];
            $question->quizz_id = $quizz->id;
            $question->uuid = Str::orderedUuid();
        }
        $question->type = $data['type'];
        $question->question = $data['question'];
        $question->expanded = $data['expanded'];
        $question->hidden = $data['hidden'];
        $question->expanded_footer = $data['expanded_footer'];
        $question->required = $data['required'];
        $question->multiple_answers = $data['multiple_answers'];
        $question->long_text = $data['long_text'];
        $question->proportion = $data['proportion'];
        $question->scroll = $data['scroll'];
        $question->save();
        return response()->json($question, 201);
    }
    public function createPostAnswers($data, $quizz){
        $question = Question::where('front_id',$data['question_id'])->first();
        if($question){
            $answers = Answer::where('question_id', $question->id)->where('front_id',$data['front_id'])->first();
            if(!$answers){
                $answers = new Answer();
            }
            $answers->question_id = $question->id;
            $answers->custom_answer = $data['custom_answer'];
            $answers->front_id = $data['front_id'];
            $answers->order = $data['order'];
            $answers->selected = $data['selected'];
            $answers->text = $data['text'];
            $answers->secondary_text = $data['secondary_text'];
            
            $answers->rank = $data['rank'];
            $answers->rank_text_min = $data['rank_text_min'];
            $answers->rank_text_max = $data['rank_text_max'];
            $answers->time_select = $data['time_select'];
            if(isset($data['image'])){
                $this->deleteOldImage($question->image);
                $answers->image = $this->moveImageAnswers($data['image']);
            }
            $answers->save();
            return response()->json($answers, 201);
        }
        return response()->json([], 201);
        
        
        
    }
}