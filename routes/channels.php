<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('newsfeed', function () {
    return true; // Public channel
});

Broadcast::channel('post-likes', function () {
    return true; // Public channel
});
