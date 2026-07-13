<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Profile;
use App\Models\Technology;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::with(['technologies', 'images'])
            ->where('is_featured', true)
            ->orderBy('order')
            ->take(3)
            ->get();

        $profile = Profile::first();

        $stacks = Technology::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('home', compact('featuredProjects', 'profile', 'stacks'));
    }
}
