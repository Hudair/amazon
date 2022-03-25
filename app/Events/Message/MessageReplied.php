<?php

namespace App\Events\Message;

use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReplied
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }
}
