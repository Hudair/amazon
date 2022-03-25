<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CustomerCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLoginInfo
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
     * @param  CustomerCreated  $event
     * @return void
     */
    public function handle(CustomerCreated $event)
    {
        //
    }
}
