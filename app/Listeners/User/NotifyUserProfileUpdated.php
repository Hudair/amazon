<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use App\Notifications\User\ProfileUpdated as ProfileUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserProfileUpdated implements ShouldQueue
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $event->user->notify(new ProfileUpdatedNotification($event->user));
    }
}
