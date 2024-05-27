<?php

namespace App\Events;

use App\Models\CoreEngine\LogicModels\Chat\ChatLogic;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('send_message');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'send_message';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message['message'],
            'chat_id' => $this->message['chat_id'],
            'sender_user_id' => $this->message['sender_user_id'],
            'id' => $this->message['id'],
            'message_type_id' => $this->message['message_type_id'],
            'time' => Carbon::now()->format('H:i'),
            'is_read' => 0
        ];
    }
}
