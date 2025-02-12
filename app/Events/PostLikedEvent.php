<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postId;
    public $likesCount;
    
    public function __construct($postId, $likesCount)
    {
        $this->postId = $postId;
        $this->likesCount = $likesCount;
    }

    
    public function broadcastOn()
    {
        return new Channel('post-likes');
    }

    public function broadcastAs()
    {
        return 'PostLikedEvent';
    }
}
