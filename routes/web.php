<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::get('/', function () {
    return view('welcome');
});


// API routes grouped with a prefix and middleware
Route::prefix('api')->group(function () {
    Route::post('/post/create', [PostController::class, 'createPost']);
    Route::get('/post/list', [PostController::class, 'getPosts']);
});

