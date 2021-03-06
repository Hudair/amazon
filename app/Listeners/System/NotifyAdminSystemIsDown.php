<?php

namespace App\Listeners\System;

use App\Events\System\DownForMaintainace;
use App\Notifications\System\SystemIsDown as SystemDownNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminSystemIsDown implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 50;

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
     * @param  DownForMaintainace  $event
     * @return void
     */
    public function handle(DownForMaintainace $event)
    {
        $event->system->superAdmin()->notify(new SystemDownNotification($event->system));
    }
}
