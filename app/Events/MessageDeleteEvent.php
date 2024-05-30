<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleteEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private $message)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->message['recipients_arr'] as $recipientId) {
            $channels[] = new PrivateChannel('notification_user.' . $recipientId);
        }
        return $channels;
    }

    public function broadcastAs()
    {
        return 'message_delete';
    }

    public function broadcastWith()
    {
        return [
            'deleted_message_id' => $this->message['id'],
            'chat_id' => $this->message['chat_id']
        ];
    }
}
