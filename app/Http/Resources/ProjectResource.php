<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TechnologyResource;
use App\Http\Resources\ProjectImageResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'type'         => $this->type,
            'role'         => $this->role,
            'repo_url'     => $this->repo_url,
            'demo_url'     => $this->demo_url,
            'thumbnail'    => $this->thumbnail ? Storage::url($this->thumbnail) : null,
            'is_featured'  => $this->is_featured,
            'order'        => $this->order,
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'images'       => ProjectImageResource::collection($this->whenLoaded('images')),
        ];
    }
}