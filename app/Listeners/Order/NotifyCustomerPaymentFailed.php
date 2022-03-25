<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderPaymentFailed;
use App\Notifications\Order\PaymentFailed as PaymentFailedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Notifications\Notification;
use Notification;

class NotifyCustomerPaymentFailed implements ShouldQueue
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
     * @param  OrderPaymentFailed  $event
     * @return void
     */
    public function handle(OrderPaymentFailed $event)
    {
        if (! config('system_settings')) {
            setSystemConfig($event->order->shop_id);
        }

        if ($event->order->customer_id) {
            $event->order->customer->notify(new PaymentFailedNotification($event->order));
        } elseif ($event->order->email) {
            Notification::route('mail', $event->order->email)
                // ->route('nexmo', '5555555555')
                ->notify(new PaymentFailedNotification($event->order));
        }
    }
}
