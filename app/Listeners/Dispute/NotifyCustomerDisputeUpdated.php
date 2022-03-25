<?php

namespace App\Listeners\Dispute;

use App\Events\Dispute\DisputeUpdated;
use App\Notifications\Dispute\Updated as DisputeUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCustomerDisputeUpdated implements ShouldQueue
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
     * @param  DisputeUpdated  $event
     * @return void
     */
    public function handle(DisputeUpdated $event)
    {
        $event->reply->repliable->customer->notify(new DisputeUpdatedNotification($event->reply));
    }
}
