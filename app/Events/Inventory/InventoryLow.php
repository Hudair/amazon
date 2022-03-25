<?php

namespace App\Events\Inventory;

use App\Models\Inventory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryLow
{
    use Dispatchable, SerializesModels;

    public $inventory;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }
}
