<?php

namespace App\Listeners\Ticket;

use App\Events\Ticket\TicketAssigned;
use App\Notifications\Ticket\TicketAssigned as TicketAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserTicketAssigned implements ShouldQueue
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
     * @param  TicketAssigned  $event
     * @return void
     */
    public function handle(TicketAssigned $event)
    {
        $event->ticket->assignedTo->notify(new TicketAssignedNotification($event->ticket));
    }
}
