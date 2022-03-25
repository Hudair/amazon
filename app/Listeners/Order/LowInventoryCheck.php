<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Notifications\Inventory\LowInventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LowInventoryCheck implements ShouldQueue
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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        if (! config('shop_settings')) {
            setShopConfig($event->order->shop_id);
        }

        if (config('shop_settings.notify_alert_quantity')) {
            $low = false;
            $items = $event->order->inventories;
            foreach ($items as $item) {
                if ($item->stock_quantity <= config('shop_settings.alert_quantity')) {
                    $low = true;
                    break;
                }
            }
            // \Log::info($items);
            if ($low) {
                $event->order->shop->notify(new LowInventory());
            }
        }
    }
}
