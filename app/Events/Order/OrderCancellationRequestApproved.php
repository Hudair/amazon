<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancellationRequestApproved
{
    use Dispatchable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
