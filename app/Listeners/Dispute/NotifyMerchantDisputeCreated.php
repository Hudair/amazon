<?php

namespace App\Listeners\Dispute;

use App\Events\Dispute\DisputeCreated;
use App\Notifications\Dispute\Created as DisputeCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMerchantDisputeCreated implements ShouldQueue
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
     * @param  DisputeCreated  $event
     * @return void
     */
    public function handle(DisputeCreated $event)
    {
        if (! config('shop_settings')) {
            setShopConfig($event->dispute->shop_id);
        }

        if (config('shop_settings.notify_new_disput')) {
            $event->dispute->shop->notify(new DisputeCreatedNotification($event->dispute));
        }
    }
}
