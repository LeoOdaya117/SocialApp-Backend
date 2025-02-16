<?php

namespace App\Http\Controllers;

use App\Models\FriendShip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FriendShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
    
        $friends = FriendShip::with(['user:id,name,avatar'])
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->get()
            ->map(function ($friend) {
                return [
                    'id' => $friend->friend_id,
                    'name'      => $friend->user->name,
                    'avatar'    => $friend->user->avatar 
                        ? Storage::url($friend->user->avatar) 
                        : null,
                ];
            });
    
        return response()->json($friends);
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
        $user = Auth::user();

        $request->validate([
            'friend_id' => 'required|exists:users,id',
            'status' => 'required|in:accepted,pending,blocked', // Use 'in' for predefined values

        ]);

        $friendShip = FriendShip::create([
            'user_id' => $user->id,
            'friend_id' => $request->friend_id,
            'status' => $request->status,
        ]);

        return response()->json($friendShip, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(FriendShip $friendShip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FriendShip $friendShip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FriendShip $friendShip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
    
        // Find the friendship where the user is involved
        $friendship = FriendShip::where('user_id', $user->id)
        ->where('friend_id', $id)
        ->first();

    
        if (!$friendship) {
            return response()->json(['message' => 'Friendship not found'], 404);
        }
    
        $friendship->delete();
    
        return response()->json(['message' => 'Friend request removed successfully']);
    }
    
    

}
