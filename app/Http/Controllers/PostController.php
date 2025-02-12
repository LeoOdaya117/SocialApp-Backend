<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Events\PostCreated;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user

        return Post::with(['user', 'likes', 'comments'])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($post) use ($user) {
                return [
                    'id' => $post->id,
                    'user' => [
                        'id' => optional($post->user)->id,
                        'name' => optional($post->user)->name ?? 'Unknown User',
                        'avatar' => optional($post->user)->avatar ? url(Storage::url($post->user->avatar)) : null,
                    ],
                    'user_id' => $post->user_id,
                    'content' => $post->content,
                    'image' => $post->image ? url(Storage::url($post->image)) : null,
                    'likes' => $post->likes->count(),
                    'comments' => $post->comments->count(),
                    'likedByUser' => $user ? $post->likes->contains('user_id', $user->id) : false, // Check if user liked the post
                    'created_at' => $post->created_at->diffForHumans(),
                ];
            });
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
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|file|image'
        ]);

      
       $path = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'image' => $path,
            

        ]);

    

        // Broadcast event safely
        try {
            broadcast(new PostCreated($post))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Broadcasting failed: ' . $e->getMessage());
        }


        return response($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response($post, 201);
    }
}
