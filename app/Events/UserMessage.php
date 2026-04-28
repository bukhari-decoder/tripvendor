<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $userId;

    public function __construct($chat, $userId)
    {
        $this->chat = $chat;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return ['user-messages.' . $this->userId];
    }
}
