<?php

namespace App\Listeners\System;

use App\Events\System\SystemInfoUpdated;
use App\Notifications\System\SystemInfoUpdated as SystemInfoUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminSystemUpdated implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 20;

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
     * @param  SystemInfoUpdated  $event
     * @return void
     */
    public function handle(SystemInfoUpdated $event)
    {
        $event->system->superAdmin()->notify(new SystemInfoUpdatedNotification($event->system));
    }
}
