<?php

use App\Http\Controllers\PostLikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Resource routes
    Route::apiResource('posts', PostController::class);

    
    Route::post('/posts/{post}/like', [PostLikeController::class, 'likePost'])->name('posts.like');

    Route::get('/topuser', [UserController::class, 'topUser'])->name('user.topuser');
    
});
