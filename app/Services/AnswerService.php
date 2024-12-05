<?php

namespace App\Services;

use App\Models\Answer;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class AnswerService
{
    public function getAllAnswers()
    {
        return Answer::all();
    }

    public function getAnswerById($id)
    {
        return Answer::findOrFail($id);
    }

    public function createAnswer(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->moveImage($data['image']);
        }

        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $data['file'] = $this->moveFile($data['file']);
        }

        return Answer::create($data);
    }

    public function updateAnswer($id, array $data)
    {
        $answer = Answer::findOrFail($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteOldFile($answer->image);
            $data['image'] = $this->moveImage($data['image']);
        }

        if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
            $this->deleteOldFile($answer->file);
            $data['file'] = $this->moveFile($data['file']);
        }

        $answer->update($data);
        return $answer;
    }

    public function deleteAnswer($id)
    {
        $answer = Answer::findOrFail($id);
        $this->deleteOldFile($answer->image);
        $this->deleteOldFile($answer->file);
        $answer->delete();
        return $answer;
    }

    private function moveImage(UploadedFile $image)
    {
        $destinationPath = public_path('answers');
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $imageName);
        return 'answers/' . $imageName;
    }

    private function moveFile(UploadedFile $file)
    {
        $destinationPath = public_path('answers');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fileName);
        return 'answers/' . $fileName;
    }

    private function deleteOldFile($filePath)
    {
        if ($filePath) {
            $file = public_path($filePath);
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
