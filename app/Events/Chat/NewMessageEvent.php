<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $text;
    public $msg_obj;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    // public $broadcastQueue = 'your-queue-name';

    /**
     * Get the message.
     *
     * @param  message  $msg_obj
     * @return void
     */
    public function __construct($msg_obj, $text)
    {
        $this->msg_obj = $msg_obj;
        $this->text = $text;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->msg_obj->customer_id) {
            $shop = $this->msg_obj instanceof \App\Models\ChatConversation ?
                $this->msg_obj->shop : $this->msg_obj->repliable->shop;

            return new Channel(get_vendor_chat_room_id($shop));
        }

        return new Channel(get_private_chat_room_id($this->msg_obj->repliable));
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $conversation = $this->msg_obj instanceof \App\Models\ChatConversation ? $this->msg_obj : $this->msg_obj->repliable;

        return [
            'conversation_id' => $conversation->id,
            'customer_id' => $conversation->customer_id,
            'text' => $this->text,
            'sender' => $this->msg_obj->getName(),
            'avatar' => $this->msg_obj->getAvatar(),
            'status' => $conversation->statusName(true),
            'time' => $conversation->updated_at->diffForHumans(),
        ];
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return true;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'zcart-chat-new-message';
    }
}
