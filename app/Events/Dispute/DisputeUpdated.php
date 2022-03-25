<?php

namespace App\Events\Dispute;

use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisputeUpdated
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }
}
