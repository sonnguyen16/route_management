<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel cho lãnh đạo theo dõi tất cả đội trưởng
Broadcast::channel('leader-dashboard', function ($user) {
    return $user->isLeader();
});

// Channel cho từng đội trưởng
Broadcast::channel('captain.{userId}', function ($user, $userId) {
    return $user->isLeader() || ((int) $user->id === (int) $userId && $user->isCaptain());
});

// Channel cho location updates
Broadcast::channel('location-updates', function ($user) {
    return $user->isLeader();
});
