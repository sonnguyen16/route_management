<?php

namespace App\Events;

use App\Models\LocationHistory;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $locationHistory;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, LocationHistory $locationHistory)
    {
        $this->user = $user;
        $this->locationHistory = $locationHistory;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('location-updates'),
            new PrivateChannel('captain.' . $this->user->id),
        ];
    }

    /**
     * Get the data that should be broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'location' => [
                'lat' => $this->locationHistory->lat,
                'lng' => $this->locationHistory->lng,
                'accuracy' => $this->locationHistory->accuracy,
                'altitude' => $this->locationHistory->altitude,
                'heading' => $this->locationHistory->heading,
                'speed' => $this->locationHistory->speed,
                'recorded_at' => $this->locationHistory->recorded_at->toISOString(),
            ]
        ];
    }

    /**
     * Get the event name to broadcast.
     */
    public function broadcastAs(): string
    {
        return 'location.updated';
    }
}
