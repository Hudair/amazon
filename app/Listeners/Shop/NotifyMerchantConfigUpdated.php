<?php

namespace App\Listeners\Shop;

use App\Events\Shop\ConfigUpdated;
use App\Notifications\Shop\ShopConfigUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMerchantConfigUpdated implements ShouldQueue
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
     * @param  ConfigUpdated  $event
     * @return void
     */
    public function handle(ConfigUpdated $event)
    {
        $event->shop->owner->notify(new ShopConfigUpdated($event->shop, $event->user));
    }
}
