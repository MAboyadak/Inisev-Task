<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Posts
Route::post('/posts',[PostController::class, 'store']);

// Website
Route::post('/website/subscribe', [WebsiteController::class, 'subscribeUser']);
