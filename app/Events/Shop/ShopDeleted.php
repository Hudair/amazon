<?php

namespace App\Events\Shop;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShopDeleted
{
    use Dispatchable, SerializesModels;

    public $shop_id;

    /**
     * Create a new job instance.
     *
     * @param  string  $shop_id
     * @return void
     */
    public function __construct($shop_id)
    {
        $this->shop_id = $shop_id;
    }
}
