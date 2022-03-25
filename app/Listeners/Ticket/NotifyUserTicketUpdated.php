<?php

namespace App\Listeners\Ticket;

use App\Events\Ticket\TicketUpdated;
use App\Notifications\Ticket\TicketUpdated as TicketUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserTicketUpdated implements ShouldQueue
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
     * @param  TicketUpdated  $event
     * @return void
     */
    public function handle(TicketUpdated $event)
    {
        $event->ticket->user->notify(new TicketUpdatedNotification($event->ticket));
    }
}
