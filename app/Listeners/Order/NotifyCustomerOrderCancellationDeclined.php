<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancellationRequestDeclined;
use App\Notifications\Order\OrderCancellationDeclined;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCustomerOrderCancellationDeclined implements ShouldQueue
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
     * @param  OrderCancellationRequestDeclined  $event
     * @return void
     */
    public function handle(OrderCancellationRequestDeclined $event)
    {
        if (! config('system_settings')) {
            setSystemConfig($event->order->shop_id);
        }

        if ($event->order->customer_id) {
            $event->order->customer->notify(new OrderCancellationDeclined($event->order));
        }
    }
}
