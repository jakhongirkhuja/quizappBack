<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Design;
use App\Models\FormPage;
use App\Models\Project;
use App\Models\Question;
use App\Models\Quizz;
use App\Models\StartPage;
use App\Models\Templete;
use App\Models\TempleteQuestion;
use App\Models\TempleteStartPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class CustomQuizzService{
    public function createInstallButtons($data, $quizz){
        if(isset($data['next_question_text'])){
            $quizz->next_question_text = $data['next_question_text'];
        }
        if(isset($data['next_question_text_uz'])){
            $quizz->next_question_text_uz = $data['next_question_text_uz'];
        }
        if(isset($data['next_to_form'])){
            $quizz->next_to_form = $data['next_to_form'];
        }
        if(isset($data['next_to_form_uz'])){
            $quizz->next_to_form = $data['next_to_form_uz'];
        }
        $quizz->save();
        return response()->json($quizz,201);
    }
    public function createInstall($data, $quizz){
        $quizz->domainType = $data['domainType'];
        $quizz->publish = filter_var($data['publish'], FILTER_VALIDATE_BOOLEAN);
        $checkUrl = Quizz::where('url', $data['url'])->where('id','!=',$quizz->id)->first();
        $inside = false;
        if(!$checkUrl){
            
            $forbiddenWords = [
                'porn', 'porno', 'xxx', 'sex', 'sexual', 'nude', 'nudity', 'erotic',
                'adult', 'fetish', 'hardcore', 'softcore', 'playboy', 'hustler', 'strip',
                'escort', 'bdsm', 'kinky', 'taboo', 'lingerie', 'camgirl', 'webcam', 'hot',
                'dirty', 'explicit', 'lewd', 'obscene', 'masturbation', 'orgasm', 'lust',
                'arousal', 'voyeur', 'incest', 'bestiality', 'rape', 'prostitute', 
                'prostitution', 'brothel', 'hookup', 'hooker', 'one-night-stand', 'swinger',
                'swinging', 'affair', 'cheating', 'underwear', 'panties', 'bra', 'bikini',
                'butt', 'boobs', 'breasts', 'vagina', 'penis', 'genitals', 'anal', 'oral',
                'threesome', 'orgy', 'gangbang', 'slut', 'whore', 'milf', 'dilf', 'hentai',
                'animeporn', 'yaoi', 'yuri', 'furry', 'smut', 'kamasutra', 'stripper',
                'shemale', 'transsexual', 'crossdressing', 'dominatrix', 'submission', 
                'dominance', 'sugarbaby', 'sugardaddy', 'lingerie', 'cam', 'peep'
            ];
            foreach ($forbiddenWords as $word) {
                if (stripos($data['url'], $word) !== false) {
                    $inside = true;
                }
            }
            if(!$inside){
                $quizz->url = $data['url'];
            }
        }
        $quizz->save();
        if($inside){
            return response()->json('',550);
        }
        if($checkUrl){
            return response()->json('', 302);
        }
        return response()->json($quizz,201);
    }
    public function createMetas($data, $quizz){
        if(isset($data['meta_image'])){
            $this->deleteOldImage($quizz->meta_image);
            $quizz->meta_image = $this->moveImageMetas($data['meta_image']);
        }
        if(isset($data['meta_favicon'])){
            $this->deleteOldImage($quizz->meta_favicon);
            $quizz->meta_favicon = $this->moveImageMetas($data['meta_favicon']);
        }
        $quizz->title = $data['title'];
        $quizz->meta_title = $data['meta_title'];
        $quizz->meta_description = $data['meta_description'];

        if(isset($data['title_uz'])){
            $quizz->title_uz = $data['title_uz'];
        }
        if(isset($data['meta_title_uz'])){
            $quizz->meta_title_uz = $data['meta_title_uz'];
        }
        if(isset($data['meta_description_uz'])){
            $quizz->meta_description_uz = $data['meta_description_uz'];
        }
        $quizz->save();
        return response()->json($quizz, 201);
    }
    public function createDesign($data, $quizz){

        $design = $quizz->design;
        if(!$design){
            $design = new Design();
            $design->quizz_id = $quizz->id;
        }
        if(isset($data['design_id'])){
            $design->design_id = $data['design_id'];
        }
        
        $design->designTitle = $data['designTitle'];
        $design->buttonColor = $data['buttonColor'];
        $design->buttonTextColor = $data['buttonTextColor'];
        $design->textColor = $data['textColor'];
        $design->bgColor = $data['bgColor'];
        $design->buttonStyle = $data['buttonStyle'];
        $design->progressBarStyle = $data['progressBarStyle'];
        $design->save();
        return response()->json($design);
    }
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
        
        $startPage->slogan_text =isset($data['slogan_text'])? $data['slogan_text'] : null;
        $startPage->slogan_text_uz = isset($data['slogan_text_uz'])? $data['slogan_text_uz'] : null;
        $startPage->title = isset($data['title'])? $data['title'] : null;
        $startPage->title_uz =  isset($data['title_uz'])? $data['title_uz'] : null;
        $startPage->title_secondary = isset($data['title_secondary'])? $data['title_secondary'] : null;
        $startPage->title_secondary_uz = isset($data['title_secondary_uz'])? $data['title_secondary_uz'] : null;
        $startPage->button_text = isset($data['button_text'])? $data['button_text'] : null;
        $startPage->button_text_uz =isset($data['button_text_uz'])? $data['button_text_uz'] : null;
        $startPage->phoneNumber = isset($data['phoneNumber'])? $data['phoneNumber'] : null ;
        $startPage->phoneNumber_uz = isset($data['phoneNumber_uz'])? $data['phoneNumber_uz'] : null ;
        $startPage->companyName_text =  isset($data['companyName_text'])? $data['companyName_text'] : null ;
        $startPage->companyName_text_uz =isset($data['companyName_text_uz'])? $data['companyName_text_uz'] : null;
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
       
        $startPage->title = isset($data['title'])? $data['title'] : null;
        $startPage->title_uz =  isset($data['title_uz'])? $data['title_uz'] : null;
        $startPage->title_secondary =isset($data['title_secondary'])? $data['title_secondary'] : null;
        $startPage->title_secondary_uz = isset($data['title_secondary_uz'])? $data['title_secondary_uz'] : null;
        $startPage->button_text = isset($data['button_text'])? $data['button_text'] : null;
        $startPage->button_text_uz =isset($data['button_text_uz'])? $data['button_text_uz'] : null;
        $startPage->name = filter_var($data['name'], FILTER_VALIDATE_BOOLEAN);
        $startPage->email =filter_var($data['email'], FILTER_VALIDATE_BOOLEAN);
        $startPage->phone = filter_var($data['phone'], FILTER_VALIDATE_BOOLEAN);
        $startPage->name_required = filter_var($data['name_required'], FILTER_VALIDATE_BOOLEAN);
        $startPage->email_required = filter_var($data['email_required'], FILTER_VALIDATE_BOOLEAN);
        $startPage->phone_required =filter_var($data['phone_required'], FILTER_VALIDATE_BOOLEAN);
      
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
    private function moveImageMetas(UploadedFile $image)
    {
        $destinationPath = public_path('metas'); // Define where to store the images
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension(); // Generate a unique name for the image
        $image->move($destinationPath, $imageName); // Move the image to the specified directory
        return 'metas/' . $imageName; // Return the relative path to store in the database
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
        $question->question = isset($data['question'])? $data['question'] : null;
        $question->question_uz = isset($data['question_uz'])? $data['question_uz'] : null;
        $question->expanded =filter_var($data['expanded'], FILTER_VALIDATE_BOOLEAN);
        $question->hidden =filter_var($data['hidden'], FILTER_VALIDATE_BOOLEAN);
        $question->self_input  = filter_var($data['self_input'], FILTER_VALIDATE_BOOLEAN);
        $question->expanded_footer = filter_var($data['expanded_footer'], FILTER_VALIDATE_BOOLEAN);
        $question->required = filter_var($data['required'], FILTER_VALIDATE_BOOLEAN); 
        $question->multiple_answers = filter_var($data['multiple_answers'], FILTER_VALIDATE_BOOLEAN);
        $question->long_text = filter_var($data['long_text'], FILTER_VALIDATE_BOOLEAN);
        $question->proportion = $data['proportion'];
        $question->scroll = filter_var($data['scroll'], FILTER_VALIDATE_BOOLEAN);
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
            $answers->custom_answer =filter_var($data['custom_answer'], FILTER_VALIDATE_BOOLEAN);
            $answers->front_id = $data['front_id'];
            $answers->order = $data['order'];
            $answers->selected = filter_var($data['selected'], FILTER_VALIDATE_BOOLEAN);
            $answers->text = isset($data['text'])? $data['text'] : null;
            $answers->text_uz = isset($data['text_uz'])? $data['text_uz'] : null;
            $answers->secondary_text =isset($data['secondary_text'])? $data['secondary_text'] : null;
            $answers->secondary_text_uz = isset($data['secondary_text_uz'])? $data['secondary_text_uz'] : null;
            
            $answers->rank = $data['rank'];
            $answers->rank_text_min = isset($data['rank_text_min'])? $data['rank_text_min'] : null;
            $answers->rank_text_min_uz =  isset($data['rank_text_min_uz'])? $data['rank_text_min_uz'] : null;
            $answers->rank_text_max =isset($data['rank_text_max'])? $data['rank_text_max'] : null;
            $answers->rank_text_max_uz = isset($data['rank_text_max_uz'])? $data['rank_text_max_uz'] : null;
            $answers->time_select = $data['time_select'];
            $answers->type_type = isset($data['type_type'])? filter_var($data['type_type'], FILTER_VALIDATE_BOOLEAN) : false;
            if(isset($data['image'])){
                $this->deleteOldImage($question->image);
                $answers->image = $this->moveImageAnswers($data['image']);
            }
            $answers->save();
            return response()->json($answers, 201);
        }
        return response()->json([], 201);
        
        
        
    }
    public function removeQuestion($data){
        $question = Question::with('answers')->where('front_id',$data['question_id'])->first();
        if($question){
            $answers = $question->answers;
            if($answers){
                foreach ($answers as $key => $answer) {
                    $this->deleteOldImage($answer->image);
                    $answer->delete();
                }
            }
            $question->delete();
        }
        return response([], 204);
    }
    public function createFromTemplete($quizz, $template_id){
        $templete = Templete::with('questions.answers','startpage')->find($template_id);
        
        if($templete){
            $quizz->title = $templete->title;
            $quizz->title_uz = $templete->title_uz;
            $quizz->meta_title = $templete->meta_title;
            $quizz->meta_title_uz = $templete->meta_title_uz;
            $quizz->meta_description = $templete->meta_description;
            $quizz->meta_description_uz = $templete->meta_description_uz;
            $quizz->meta_favicon = $templete->meta_favicon;
            $quizz->meta_image = $templete->meta_image;
            $quizz->next_question_text = $templete->next_question_text;
            $quizz->next_question_text_uz = $templete->next_question_text_uz;
            $quizz->next_to_form = $templete->next_to_form;
            $quizz->next_to_form_uz = $templete->next_to_form_uz;
            
            
            $templeteStartPage = $templete->startpage;

            if($templeteStartPage){
                $quizz->startPage = true;
                $startPage = StartPage::where('quizz_id',$quizz->id )->first(); 
                $startPage->hero_image = $templeteStartPage->hero_image;
                $startPage->hero_image_mobi = $templeteStartPage->hero_image_mobi;
                $startPage->logo = $templeteStartPage->logo;
                $startPage->slogan_text = $templeteStartPage->slogan_text;
                $startPage->slogan_text_uz = $templeteStartPage->slogan_text_uz;
                $startPage->title = $templeteStartPage->title;
                $startPage->title_uz = $templeteStartPage->title_uz;
                $startPage->title_secondary = $templeteStartPage->title_secondary;
                $startPage->title_secondary_uz = $templeteStartPage->title_secondary_uz;
                $startPage->button_text = $templeteStartPage->button_text;
                $startPage->button_text_uz = $templeteStartPage->button_text_uz;
                $startPage->phoneNumber = $templeteStartPage->phoneNumber;
                $startPage->phoneNumber_uz = $templeteStartPage->phoneNumber_uz;
                $startPage->companyName_text = $templeteStartPage->companyName_text;
                $startPage->companyName_text_uz = $templeteStartPage->companyName_text_uz;
                $startPage->design_type = $templeteStartPage->design_type;
                $startPage->design_alignment = $templeteStartPage->design_alignment;
                $startPage->save();
            }
            $quizz->save();
            $templeteQuestions = TempleteQuestion::with('answers')->where('templete_id',$template_id )->get();
            foreach($templeteQuestions as $index => $templeteQuestion){
                $question= new Question();
                $question->front_id  =Str::orderedUuid()->toString();
                $question->quizz_id = $quizz->id;
                $question->uuid = Str::orderedUuid()->toString();
                $question->type = $templeteQuestion->type;
                $question->question = $templeteQuestion->question;
                $question->question_uz = $templeteQuestion->question_uz;
                $question->order = $index+1;
                $question->save();
                $templeteAnswers = $templeteQuestion->answers;
                foreach ($templeteAnswers as $key => $templeteAnswer) {
                    $answer = new Answer();
                    $answer->front_id = Str::orderedUuid()->toString();
                    $answer->question_id =$question->id;
                    $answer->image = $templeteAnswer->image;
                    $answer->image = $templeteAnswer->image;
                    $answer->text = $templeteAnswer->text;
                    $answer->text_uz = $templeteAnswer->text_uz;
                    $answer->secondary_text = $templeteAnswer->secondary_text;
                    $answer->secondary_text_uz = $templeteAnswer->secondary_text_uz;
                    $answer->time_select = $templeteAnswer->time_select;
                    $answer->rank = $templeteAnswer->rank;
                    $answer->rank_text_min = $templeteAnswer->rank_text_min;
                    $answer->rank_text_min_uz = $templeteAnswer->rank_text_min_uz;
                    $answer->rank_text_max = $templeteAnswer->rank_text_max;
                    $answer->rank_text_max_uz = $templeteAnswer->rank_text_max_uz;
                    $answer->order = $key+1;
                    $answer->save();
                }
            }
            return response()->json($quizz);
        }
        return response()->json([], 404);
    }
    public function removeProject($project){
        $quizes = $project->quizzs;
        try {
            foreach ($quizes as $quiz) {
                $questions = $quiz->questions;
                foreach ($questions as $question) {
                    $answers = $question->answers;
                    if($answers){
                        foreach ($answers as $key => $answer) {
                            $this->deleteOldImage($answer->image);
                            $answer->delete();
                        }
                    }
                    $this->deleteOldImage($question->image);
                    $question->delete();
                }
                $quiz->delete();
            }
            $project->delete();
            return response()->json(null,204);
        } catch (\Throwable $th) {
            return response()->json(null, 502);
        }
        
    }
    public function removeQuizz($quiz){
        
        try {
            $questions = $quiz->questions;
            foreach ($questions as $question) {
                $answers = $question->answers;
                if($answers){
                    foreach ($answers as $key => $answer) {
                        $this->deleteOldImage($answer->image);
                        $answer->delete();
                    }
                }
                $this->deleteOldImage($question->image);
                $question->delete();
            }
            $quiz->delete();
            return response()->json(null,204);
        } catch (\Throwable $th) {
            return response()->json(null, 502);
        }
    }
    public function duplicateQuestions($data){
        $question = Question::with('answers')->where('front_id',$data['question_id'])->first();
        if($question){
            $newQuestion = $question->replicate();
            $newQuestion->save();
            $arrayAnswer =[];
            foreach ($question->answers as $answer) {
                $newAnswer = $answer->replicate();
                $newAnswer->question_id = $newQuestion->id; 
                $newAnswer->save();
                $arrayAnswer[] = $newAnswer;
            }
            $d['question']= $newQuestion;
            $d['answers']=$arrayAnswer;
            return response()->json($d, 201);
        }
    }
}