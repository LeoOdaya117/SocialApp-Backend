<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendShip extends Model
{

    protected $table = "friendships";
    protected $fillable = ['user_id', 'friend_id', 'status'];
    public function user(){
        return $this->belongsTo(User::class, 'friend_id');
    }
}
