<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['technologies'])
            ->orderBy('order')
            ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    public function show(string $slug)
    {
        $project = Project::with(['technologies', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }
}