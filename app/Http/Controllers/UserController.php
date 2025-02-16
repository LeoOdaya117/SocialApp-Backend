<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUserId = Auth::id(); // Get the logged-in user ID

        return User::select('id', 'name', 'avatar')
            ->withCount('friends') // Count total friends of each user
            ->withCount([
                'friends as mutual_friends_count' => function ($query) use ($authUserId) {
                    $query->whereIn('friend_id', function ($subQuery) use ($authUserId) {
                        // Get the authenticated user's accepted friends
                        $subQuery->select('friend_id')
                            ->from('friendships')
                            ->where('user_id', $authUserId)
                            ->where('status', 'accepted'); // Only count accepted friends
                    });
                }
            ])
            ->addSelect([
                'friendship_status' => \DB::table('friendships')
                    ->whereColumn('friendships.friend_id', 'users.id')
                    ->where('friendships.user_id', $authUserId)
                    ->select('friendships.status') // Select the status field
                    ->limit(1) // Ensure only one status is returned
            ])
            ->where('name', 'LIKE', "%{$request->q}%")
            ->get();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'avatar' => $request->user()->avatar ? url(Storage::url($request->user()->avatar)) : null,
            'cover' => $request->user()->cover ? url(Storage::url($request->user()->cover)) : null,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
    
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile image
        ]);
    
        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Hash password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
    
            // Store new avatar in storage/app/public/avatars
            $path = $request->hasFile('avatar') ? $request->file('avatar')->store('images', 'public') : null;

            $user->avatar =  $path ; // Save path as `storage/avatars/filename.jpg`
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset($user->avatar) : null, // Returns full URL for the image
            ]
        ]);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function topUser(){

  

        return $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($topUsers){
                return [
                    'id' => $topUsers->id,
                    'name' => $topUsers->name,
                    'avatar' => $topUsers->avatar ? url(Storage::url($topUsers->avatar)) : null,
                    'posts_count' => $topUsers->posts_count
                ];

            });


       
    }
}
