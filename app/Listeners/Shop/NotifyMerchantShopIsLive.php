<?php

namespace App\Listeners\Shop;

use App\Events\Shop\ShopIsLive;
use App\Notifications\Shop\ShopIsLive as ShopIsLiveNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMerchantShopIsLive implements ShouldQueue
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
     * @param  ShopIsLive  $event
     * @return void
     */
    public function handle(ShopIsLive $event)
    {
        $event->shop->owner->notify(new ShopIsLiveNotification($event->shop));
    }
}
