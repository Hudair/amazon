<?php

namespace App\Listeners\User;

use App\Notifications\User\PasswordUpdated as PasswordUpdateNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserPasswordUpdated implements ShouldQueue
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $event->user->notify(new PasswordUpdateNotification($event->user));
    }
}
