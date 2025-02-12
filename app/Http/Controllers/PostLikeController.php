<?php

namespace App\Http\Controllers;

use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PostLikedEvent;

class PostLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function likePost(Request $request, $postId)
    {
        $user = Auth::user();

        // Check if the user has already liked the post
        $existingLike = PostLike::where('post_id', $postId)->where('user_id', $user->id)->first();

        if (!$existingLike) {
            PostLike::create([
                'post_id' => $postId,
                'user_id' => $user->id,
            ]);
        }else{
            $existingLike->delete();
        }

        // Count total likes for the post
        $likesCount = PostLike::where('post_id', $postId)->count();

        broadcast(new PostLikedEvent($postId, $likesCount));

        return response()->json(['likes' => $likesCount]);
    }

    public function destroy($postId)
    {
        $user = Auth::user();

        // Remove like if it exists
        PostLike::where('post_id', $postId)->where('user_id', $user->id)->delete();

        // Count total likes for the post
        $likesCount = PostLike::where('post_id', $postId)->count();

        return response()->json(['likes' => $likesCount]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PostLike $postLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostLike $postLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostLike $postLike)
    {
        //
    }

}
