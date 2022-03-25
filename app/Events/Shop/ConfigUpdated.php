<?php

namespace App\Events\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfigUpdated
{
    use Dispatchable, SerializesModels;

    public $shop;
    public $user;

    /**
     * Create a new job instance.
     *
     * @param  Shop  $shop
     * @return void
     */
    public function __construct(Shop $shop, User $user)
    {
        $this->shop = $shop;
        $this->user = $user;
    }
}
