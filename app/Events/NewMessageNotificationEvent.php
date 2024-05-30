<?php

namespace App\Events;

use App\Models\CoreEngine\LogicModels\Notification\NotificationLogic;
use App\Models\CoreEngine\LogicModels\User\UserLogic;
use App\Models\CoreEngine\ProjectModels\Chat\ChatUser;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotificationEvent implements ShouldBroadcast
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
            $this->storeMessageNotification($recipientId, $this->message['sender_user_id']);
            $channels[] = new PrivateChannel('notification_user.' . $recipientId);
        }
        return $channels;
    }

    public function storeMessageNotification($receiverId, $senderId) {
        $notification['message'] = 'У Вас новое сообщение от ' . (new UserLogic(['id' => (string) $senderId]))->getUserName();
        $notification['user_id'] = $receiverId;
        return (new NotificationLogic())->store($notification);
    }

    public function broadcastAs()
    {
        return 'new_message_notification';
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
