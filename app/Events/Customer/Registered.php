<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Registered
{
    use Dispatchable, SerializesModels;

    public $customer;

    /**
     * Create a new job instance.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}
