<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancellationRequestCreated;
use App\Notifications\Order\AcknowledgeOrderCancellationRequest as OrderCancellationRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyCustomerOrderCancellationRequest implements ShouldQueue
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
     * @param  OrderCancellationRequestCreated  $event
     * @return void
     */
    public function handle(OrderCancellationRequestCreated $event)
    {
        if (! config('system_settings')) {
            setSystemConfig($event->order->shop_id);
        }

        if ($event->order->customer_id) {
            $event->order->customer->notify(new OrderCancellationRequest($event->order));
        }
    }
}
