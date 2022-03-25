<?php

namespace App\Events\Shop;

use App\Models\Shop;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShopUpdated
{
    use Dispatchable, SerializesModels;

    public $shop;

    /**
     * Create a new job instance.
     *
     * @param  Shop  $shop
     * @return void
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }
}
