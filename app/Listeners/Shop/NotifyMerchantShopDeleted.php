<?php

namespace App\Listeners\Shop;

use App\Events\Shop\ShopDeleted;
use App\Notifications\Shop\ShopDeleted as ShopDeletedNotification;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMerchantShopDeleted implements ShouldQueue
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
     * @param  ShopDeleted  $event
     * @return void
     */
    public function handle(ShopDeleted $event)
    {
        $shop = Shop::withTrashed()->find($event->shop_id);
        $merchant = User::withTrashed()->find($shop->owner_id);

        $merchant->notify(new ShopDeletedNotification());
    }
}
