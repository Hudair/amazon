<?php
namespace Incevio\Package\Wallet\Services;

use Auth;
use App\Shop;
use App\Customer;
use Illuminate\Http\Request;

class RefundToWallet
{
    public $sender;

    public $receiver;

    public $amount;

    public $meta;

    public $forceTransfer;

    /**
     * Set sender.
     *
     * @return self
     */
    public function sender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set receiver.
     *
     * @return self
     */
    public function receiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Set amount.
     *
     * @return self
     */
    public function amount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Set Meta infos.
     *
     * @return self
     */
    public function meta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    public function forceTransfer()
    {
        $this->forceTransfer = True;

        return $this;
    }

    /**
     * Execute the transection.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->forceTransfer) {
           return $this->sender->forceTransfer($this->receiver, $this->amount, $this->meta);
        }

       return $this->sender->transfer($this->receiver, $this->amount, $this->meta);
    }
}