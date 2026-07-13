<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageViewController;

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
Route::get('/technologies', [TechnologyController::class, 'index']);
Route::get('/profile', [ProfileController::class, 'show']);

Route::post('/messages', [MessageController::class, 'store'])
    ->middleware('throttle:5,1');

    Route::post('/pageview', [PageViewController::class, 'store'])
    ->middleware('throttle:60,1');

Route::post('/pageview/{id}/duration', [PageViewController::class, 'updateDuration'])
    ->middleware('throttle:60,1');
