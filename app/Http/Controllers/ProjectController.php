<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected $projectService;
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    public function index(): JsonResponse
    {
        $projects = $this->projectService->getAllProjects();
        
        return response()->json($projects);
    }

    public function show($id): JsonResponse
    {
        $project = $this->projectService->getProjectById($id);
        return response()->json($project);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->validated());
        return response()->json($project, 201);
    }

    public function update(ProjectRequest $request, $id): JsonResponse
    {
        $project = $this->projectService->updateProject($id, $request->validated());
        return response()->json($project);
    }

    public function destroy($id): JsonResponse
    {
        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Project deleted successfully'],204);
    }
}
