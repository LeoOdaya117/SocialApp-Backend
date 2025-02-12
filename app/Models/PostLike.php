<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = "post_likes";

    protected $fillable = ['post_id', 'user_id'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
