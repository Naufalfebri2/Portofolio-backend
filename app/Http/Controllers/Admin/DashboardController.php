<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Message;
use App\Models\Technology;
use App\Models\Profile;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $featuredProjects = Project::where('is_featured', true)->count();

        $unreadMessages = Message::where('is_read', false)->count();
        $totalMessages = Message::count();

        $totalTechnologies = Technology::count();

        $resumeDownloads = Profile::first()->download_count ?? 0;

        return view('admin.dashboard', compact(
            'totalProjects',
            'featuredProjects',
            'unreadMessages',
            'totalMessages',
            'totalTechnologies',
            'resumeDownloads',
        ));
    }
}
