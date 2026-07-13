<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TechnologyController extends Controller
{
    public function index()
    {
        return view('admin.technologies.index');
    }
}
