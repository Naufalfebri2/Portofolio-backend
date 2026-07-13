<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $profile = Profile::first();

        return view('about', compact('profile'));
    }
}
