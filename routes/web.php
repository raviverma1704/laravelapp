<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function () {
    return view('testpage');
});

// API routes grouped with a prefix and middleware
Route::prefix('api')->group(function () {
    Route::post('/post/create', [PostController::class, 'createPost']);
    Route::get('/post/list', [PostController::class, 'getPosts']);

    Route::get('/test', function () {
        return "Hello World!!!";
    });


    Route::get('/check-deploy', function () {
        return "Code deployed to staging server!!";
    });
});

