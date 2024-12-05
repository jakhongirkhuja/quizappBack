<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Quizz;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class QuizzService
{
    public function getAllQuizzes()
    {
        if(request()->project_id){
            $project = Project::with('quizzs.leads.visitLog')->where('uuid', request()->project_id)->where('user_id',Auth::id())->first();
            if($project){
                return $project->quizzs()->latest()->get();
            }
        }
        return [];
    }

    public function getQuizzById($id)
    {
        return Quizz::findOrFail($id);
    }

    public function createQuizz(array $data)
    {
        if (isset($data['meta_favicon']) && $data['meta_favicon'] instanceof UploadedFile) {
            // Store the favicon image
            $data['meta_favicon'] = $this->moveImage($data['meta_favicon']);
        }
        if (isset($data['meta_image']) && $data['meta_image'] instanceof UploadedFile) {
            // Store the meta image
            $data['meta_image'] = $this->moveImage($data['meta_image']);
        }

        $data['uuid'] = Str::uuid();
        return Quizz::create($data);
    }

    public function updateQuizz($id, array $data)
    {
        $quizz = Quizz::findOrFail($id);

        if (isset($data['meta_favicon']) && $data['meta_favicon'] instanceof UploadedFile) {
            // If the favicon image is updated, delete the old one and upload the new one
            $this->deleteOldImage($quizz->meta_favicon);
            $data['meta_favicon'] = $this->moveImage($data['meta_favicon']);
        }

        if (isset($data['meta_image']) && $data['meta_image'] instanceof UploadedFile) {
            // If the meta image is updated, delete the old one and upload the new one
            $this->deleteOldImage($quizz->meta_image);
            $data['meta_image'] = $this->moveImage($data['meta_image']);
        }

        $quizz->update($data);
        return $quizz;
    }

    public function deleteQuizz($id)
    {
        $quizz = Quizz::findOrFail($id);
        $this->deleteOldImage($quizz->meta_favicon);
        $this->deleteOldImage($quizz->meta_image);
        $quizz->delete();
        return $quizz;
    }

    /**
     * Move the uploaded image to the 'public/images' directory.
     * 
     * @param UploadedFile $image
     * @return string The path to the image
     */
    private function moveImage(UploadedFile $image)
    {
        $destinationPath = public_path('quizz'); // Define where to store the images
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension(); // Generate a unique name for the image
        $image->move($destinationPath, $imageName); // Move the image to the specified directory
        return 'quizz/' . $imageName; // Return the relative path to store in the database
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
}
