<?php

namespace App\Listeners\Refund;

use App\Events\Refund\RefundApproved;
use App\Notifications\Refund\Approved as RefundApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class NotifyCustomerRefundApproved implements ShouldQueue
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
     * @param  RefundApproved  $event
     * @return void
     */
    public function handle(RefundApproved $event)
    {
        if (! config('system_settings')) {
            setSystemConfig($event->refund->shop_id);
        }

        if ($event->refund->customer_id) {
            $notifiable = $event->refund->order->customer;
        } elseif ($event->refund->order->email) { // Customer is a guest
            $notifiable = Notification::route('mail', $event->refund->order->email);
        } else {
            $notifiable = null;
        }

        if ($notifiable) {
            $notifiable->notify(new RefundApprovedNotification($event->refund));
        }
    }
}
