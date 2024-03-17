<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnlineStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $status)
    {
        $this->userId = $userId;
        $this->status = $status;
        // $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('useronline');
    }

    // public function broadcastAs()
    // {
    //     return 'user';
    // }

    // public function broadcastWith()
    // {
    //     return [
    //         'userId' => $this->userId,
    //         'status' => $this->status,
    //     ];
    // }
}
