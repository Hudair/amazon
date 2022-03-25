<?php

namespace App\Listeners\System;

use App\Events\System\SystemIsLive;
use App\Notifications\System\SystemIsLive as SystemIsLiveNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminSystemIsLive implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

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
     * @param  SystemIsLive  $event
     * @return void
     */
    public function handle(SystemIsLive $event)
    {
        $event->system->superAdmin()->notify(new SystemIsLiveNotification($event->system));
    }
}
