<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TechnologyResource;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    public function index(): JsonResponse
    {
        $technologies = Technology::orderBy('name')->get();

        return response()->json([
            'data' => TechnologyResource::collection($technologies),
        ]);
    }
}