<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ResumeController extends Controller
{
    public function download()
    {
        $profile = Profile::first();

        if (!$profile || !$profile->resume_path || !Storage::disk('public')->exists($profile->resume_path)) {
            abort(404, 'CV file not found.');
        }

        $profile->increment('download_count');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($profile->resume_path, 'CV-' . str_replace(' ', '-', $profile->name) . '.pdf');
    }
}
