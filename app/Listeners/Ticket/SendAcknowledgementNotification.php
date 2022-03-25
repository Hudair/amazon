<?php

namespace App\Listeners\Ticket;

use App\Events\Ticket\TicketCreated;
use App\Notifications\Ticket\SendAcknowledgement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAcknowledgementNotification implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TicketCreated  $event
     * @return void
     */
    public function handle(TicketCreated $event)
    {
        $event->ticket->user->notify(new SendAcknowledgement($event->ticket));
    }
}
