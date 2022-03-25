<?php

namespace App\Listeners\Refund;

use App\Events\Refund\RefundInitiated;
use App\Notifications\Refund\Approved as RefundApprovedNotification;
use App\Notifications\Refund\Declined as RefundDeclinedNotification;
use App\Notifications\Refund\Initiated as RefundInitiatedNotification;
use App\Models\Refund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class NotifyCustomerRefundInitiated implements ShouldQueue
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
     * @param  RefundInitiated  $event
     * @return void
     */
    public function handle(RefundInitiated $event)
    {
        if ($event->notify_customer) {
            if (! config('system_settings')) {
                setSystemConfig($event->refund->shop_id);
            }

            $notifiable = null;

            if ($event->refund->customer_id) {
                $notifiable = $event->refund->order->customer;
            } elseif ($event->refund->order->email) {  // Customer is a guest
                $notifiable = Notification::route('mail', $event->refund->order->email);
            }

            if ($notifiable) {
                if ($event->refund->status == Refund::STATUS_APPROVED) {
                    $notifiable->notify(new RefundApprovedNotification($event->refund));
                } elseif ($event->refund->status == Refund::STATUS_DECLINED) {
                    $notifiable->notify(new RefundDeclinedNotification($event->refund));
                } else {
                    $notifiable->notify(new RefundInitiatedNotification($event->refund));
                }
            }
        }
    }
}
