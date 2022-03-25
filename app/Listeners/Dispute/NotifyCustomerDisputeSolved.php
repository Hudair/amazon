<?php

namespace App\Listeners\Dispute;

use App\Events\Dispute\DisputeSolved;
use App\Notifications\Dispute\Solved as DisputeSolvedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCustomerDisputeSolved implements ShouldQueue
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
     * @param  DisputeSolved  $event
     * @return void
     */
    public function handle(DisputeSolved $event)
    {
        $event->dispute->customer->notify(new DisputeSolvedNotification($event->dispute));
    }
}
