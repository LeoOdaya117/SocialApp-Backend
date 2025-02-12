<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'is_friend' => \DB::table('friendships')
                    ->whereColumn('friendships.friend_id', 'users.id')
                    ->where('friendships.user_id', $authUserId)
                    ->where('friendships.status', 'accepted') // Check only accepted friendships
                    ->selectRaw('COUNT(*) > 0') // Returns true if they are accepted friends
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
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
            ->get();


       
    }
}
