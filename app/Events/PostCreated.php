<?php


namespace App\Events;
use Illuminate\Support\Facades\Storage; // ✅ Move this above the namespace

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;

class PostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = [
            'id' => $post->id,
            'user' => [
                'id' => optional($post->user)->id,
                'name' => optional($post->user)->name ?? 'Unknown User',
                'avatar' => optional($post->user)->avatar ? url(Storage::url($post->user->avatar)) : null,
            ],
            'content' => $post->content,
            'image' => $post->image ? url(Storage::url($post->image)) : null, // ✅ Fix the image URL
            'likes' => $post->likes->count(),
           
            'comments' => $post->comments->count(),
            'created_at' => $post->created_at->diffForHumans(),
        ];
    }


    public function broadcastOn()
    {
        return new Channel('newsfeed');
    }

    public function broadcastWith()
    {
        return ['post' => $this->post];
    }
}
