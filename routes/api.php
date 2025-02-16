<?php

use App\Http\Controllers\FriendShipController;
use App\Http\Controllers\PostLikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    // API Resource routes
    Route::apiResource('posts', PostController::class);
    Route::apiResource('friends', FriendShipController::class);

    // Post Like
    Route::post('/posts/{post}/like', [PostLikeController::class, 'likePost'])->name('posts.like');

    // User-related routes
    Route::get('/topuser', [UserController::class, 'topUser'])->name('user.topuser');
    Route::get('/people', [UserController::class, 'index'])->name('people');

    // Trending posts
    Route::get('/trendingpost', [PostController::class, 'trendingPost'])->name('post.trending');

    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/update', [UserController::class, 'update']);

    Route::apiResource('/friendship',FriendShipController::class);
});


