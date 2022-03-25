<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Contracts\PaymentServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentService implements PaymentServiceContract
{
    const STATUS_INITIATED = 'initiated';        // Default
    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    // const STATUS_VALIDATING = 'validating';
    const STATUS_ERROR = 'error';

    public $request;
    public $payee;
    public $receiver;
    public $order;
    public $fee;
    public $amount;
    public $currency_code;
    public $meta;
    public $description;
    public $sandbox;
    // public $success;
    public $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->currency_code = get_currency_code();

        // Set the default status
        $this->status = self::STATUS_INITIATED;

        // Get payee model
        if ($this->request->has('payee')) {
            $this->setPayee($this->request->payee);
        } elseif (Auth::guard('customer')->check()) {
            $this->setPayee(Auth::guard('customer')->user());
        } elseif (Auth::guard('api')->check()) {
            $this->setPayee(Auth::guard('api')->user());
        } elseif (Auth::guard('web')->check() && Auth::user()->isMerchant()) {
            $this->setPayee(Auth::user()->owns);
        }
    }

    /**
     * Set the payee
     * return $this
     */
    public function setPayee($payee)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Set the payable amount
     * return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Set the description
     * return $this
     */
    public function setDescription($description = '')
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the receiver
     * return $this
     */
    public function setReceiver($receiver = 'platform')
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Set order info
     *
     * @param Order $order | array
     * @return self
     */
    public function setOrderInfo($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Return order id or the last order id
     * return $this
     */
    public function getOrderId()
    {
        if ($this->order) {
            if (is_array($this->order)) {
                return implode('-', array_column($this->order, 'id'));
            }

            if (!$this->order instanceof Order) {
                $this->order = Order::findOrFail($this->order);
            }

            return $this->order->id;
        }

        return null;
    }

    /**
     * Set payment gate configs
     * Overwite on child class
     */
    public function setConfig()
    {
        return $this;
    }

    /**
     * The payment will execute here, overwite on child class
     */
    public function charge()
    {
        // Set the status as awaiting to process the payment later
        $this->status = self::STATUS_PENDING;

        return $this;
    }
}
