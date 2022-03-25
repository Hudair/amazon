<?php

namespace App\Listeners\Announcement;

use App\Events\Announcement\AnnouncementCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAnnouncementCreatedNotification
{
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
     * @param  AnnouncementCreated  $event
     * @return void
     */
    public function handle(AnnouncementCreated $event)
    {
        //
    }
}
