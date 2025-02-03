<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;

class NewMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat; // Data chat yang baru

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        // Pakai channel 'chat' atau tambahin logic lain
        return new Channel("chat");
    }
}
