<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class QuestionService
{
    public function getAllQuestions()
    {
        return Question::all();
    }

    public function getQuestionById($id)
    {
        return Question::findOrFail($id);
    }

    public function createQuestion(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->moveImage($data['image']);
        }

        $data['uuid'] = Str::uuid();
        return Question::create($data);
    }

    public function updateQuestion($id, array $data)
    {
        $question = Question::findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteOldImage($question->image);
            $data['image'] = $this->moveImage($data['image']);
        }

        $question->update($data);
        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $this->deleteOldImage($question->image);
        $question->delete();
        return $question;
    }

    
    private function moveImage(UploadedFile $image)
    {
        $destinationPath = public_path('questions'); // Define where to store the images
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension(); // Generate a unique name for the image
        $image->move($destinationPath, $imageName); // Move the image to the specified directory
        return 'questions/' . $imageName; // Return the relative path to store in the database
    }

    
    private function deleteOldImage($imagePath)
    {
        if ($imagePath) {
            $filePath = public_path($imagePath);
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the image file
            }
        }
    }
}
