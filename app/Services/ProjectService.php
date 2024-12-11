<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectService
{
    public function getAllProjects()
    {
        return Project::with('quizzs.startPage','quizzs.formPage')->where('user_id', Auth::id())->get();
    }

    public function getProjectById($id)
    {
        return Project::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
    }
    public function getProjectByUuid($uuid)
    {
        return Project::where('user_id', Auth::id())->where('uuid', $uuid)->firstOrFail();
    }

    public function createProject(array $data)
    {
        $data['uuid'] = Str::uuid();
        $data['user_id'] =  Auth::id();
        return Project::create($data);
    }

    public function updateProject($id, array $data)
    {
        $project = $this->getProjectById($id);
        $project->update($data);
        return $project;
    }

    public function deleteProject($id)
    {
        $project = $this->getProjectById($id);
        $project->delete();
        return $project;
    }
}
