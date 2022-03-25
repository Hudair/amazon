<?php

namespace App\Events\Message;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage
{
    use Dispatchable, SerializesModels;

    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
