<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'           => $this->name,
            'bio'            => $this->bio,
            'photo'          => $this->photo ? Storage::url($this->photo) : null,
            'resume_url'     => $this->resume_path ? Storage::url($this->resume_path) : null,
            'phone'          => $this->phone,
            'email'          => $this->email,
            'location'       => $this->location,
            'github_url'     => $this->github_url,
            'linkedin_url'   => $this->linkedin_url,
            'instagram_url'  => $this->instagram_url,
        ];
    }
}