<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo',
        'resume_path',
        'download_count',
        'phone',
        'email',
        'location',
        'github_url',
        'linkedin_url',
        'instagram_url',
    ];
}
