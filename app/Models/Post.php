<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $table = "posts";


    protected $fillable = ['user_id','content','image', 'privacy'];

    // Relationship: Each post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes(){
        return $this->hasMany(PostLike::class);
    }
    public function comments(){
        return $this->hasMany(PostComment::class);
    }
}
