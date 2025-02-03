<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chatIds;       // array of chat IDs read
    public $readerId;      // user who read the message
    public $senderId;      // lawan chat

    public function __construct($chatIds, $readerId, $senderId)
    {
        $this->chatIds = $chatIds;
        $this->readerId = $readerId;   // misalnya Seller
        $this->senderId = $senderId;   // misalnya Buyer
    }

    public function broadcastOn()
    {
        // broadcast ke channel 'chat'
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'MessageRead';
    }
}

