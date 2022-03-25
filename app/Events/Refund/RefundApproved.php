<?php

namespace App\Events\Refund;

use App\Models\Refund;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundApproved
{
    use Dispatchable, SerializesModels;

    public $refund;
    public $notify_customer;

    /**
     * Create a new job instance.
     *
     * @param  Refund  $refund
     * @return void
     */
    public function __construct(Refund $refund, $notify_customer = null)
    {
        $this->refund = $refund;
        $this->notify_customer = $notify_customer;
    }
}
