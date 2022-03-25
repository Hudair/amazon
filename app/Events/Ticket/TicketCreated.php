<?php

namespace App\Events\Ticket;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCreated
{
    use Dispatchable, SerializesModels;

    public $ticket;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
}
