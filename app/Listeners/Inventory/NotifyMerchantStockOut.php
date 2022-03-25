<?php

namespace App\Listeners\Inventory;

use App\Events\Inventory\StockOut;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMerchantStockOut implements ShouldQueue
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
     * @param  StockOut  $event
     * @return void
     */
    public function handle(StockOut $event)
    {
        //
    }
}
