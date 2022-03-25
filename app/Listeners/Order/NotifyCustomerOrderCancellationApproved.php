<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancellationRequestApproved;
use App\Notifications\Order\OrderCancellationApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCustomerOrderCancellationApproved implements ShouldQueue
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
     * @param  OrderCancellationRequestApproved  $event
     * @return void
     */
    public function handle(OrderCancellationRequestApproved $event)
    {
        if (! config('system_settings')) {
            setSystemConfig($event->order->shop_id);
        }

        if ($event->order->customer_id) {
            $event->order->customer->notify(new OrderCancellationApproved($event->order));
        }
    }
}
