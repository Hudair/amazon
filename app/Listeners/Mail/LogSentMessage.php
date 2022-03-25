<?php

namespace App\Listeners\Mail;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentMessage
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
     * @param  InventoryLow  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        Log::info('........................... Message Sent Successfully .................................');
    }
}
