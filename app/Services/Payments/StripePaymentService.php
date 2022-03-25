<?php

namespace App\Services\Payments;

use Stripe\Stripe;
use App\Models\Customer;
use Illuminate\Http\Request;

class StripePaymentService extends PaymentService
{
    public $stripe_account_id;
    public $token;
    public $card;
    public $fee;
    public $meta;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function setConfig()
    {
        $this->setStripAccountId();

        // When the request has card info
        if ($this->request->card_number) {
            $this->setCardInfo();
        }

        $this->setStripeToken();

        return $this;
    }

    public function charge()
    {
        $data = [
            'amount' => get_cent_from_doller($this->amount),
            'currency' => get_currency_code(),
            'description' => $this->description,
            'metadata' => $this->meta,
        ];

        if ($this->chargeSavedCustomer()) {
            $data['customer'] = $this->payee->stripe_id;
        } else {
            $data['source'] = $this->token;
        }

        // Set application fee if merchant get paid
        if (
            $this->receiver == 'merchant' &&
            $this->order && $this->payee instanceof Customer
        ) {
            // Set platform fee for order if not already set
            if (!$this->fee) {
                $this->setPlatformFee(getPlatformFeeForOrder($this->order));
            }

            // if ($this->fee) {
            $data['application_fee'] = $this->fee;
            // }
        }

        $result = \Stripe\Charge::create($data, [
            'stripe_account' => $this->stripe_account_id,
        ]);

        if ($result->status == 'succeeded') {
            $this->status = self::STATUS_PAID;
        } else {
            $this->status = self::STATUS_ERROR;
        }

        return $this;
    }

    public function setPlatformFee($fee = 0)
    {
        $this->fee = $fee > 0 ? get_cent_from_doller($fee) : 0;

        return $this;
    }

    private function setStripeToken()
    {
        if ($this->receiver == 'merchant' && $this->chargeSavedCustomer()) {
            $token = \Stripe\Token::create([
                'customer' => $this->payee->stripe_id,
            ], ['stripe_account' => $this->stripe_account_id]);

            $this->token = $token->id;
        } elseif ($this->card) {
            $token = \Stripe\Token::create([
                'card' => $this->card,
            ], ['stripe_account' => $this->stripe_account_id]);

            $this->token = $token;
        } else {
            $this->token = $this->request->cc_token;
        }
    }

    public function setOrderInfo($order)
    {
        $this->order = $order;

        // If multiple orders take the info from last one
        if (is_array($this->order)) {
            $order = reset($order);
        }

        $this->meta = [
            'order_number' => $order->order_number,
            'shipping_address' => strip_tags($order->shipping_address),
            'buyer_note' => $order->buyer_note,
        ];

        return $this;
    }

    private function setStripAccountId()
    {
        if ($this->order && $this->receiver == 'merchant') {
            $this->stripe_account_id = $this->order->shop->config->stripe->stripe_user_id;
        } else {
            $this->stripe_account_id = config('services.stripe.account_id');
        }
    }

    private function chargeSavedCustomer()
    {
        return $this->payee && $this->payee->hasBillingToken() &&
            ($this->request->has('remember_the_card') ||
                $this->request->payment_method == 'saved_card');
    }

    /* Set the card info */
    public function setCardInfo()
    {
        $this->card = [
            'number' => $this->request->card_number,
            'exp_month' => $this->request->exp_month,
            'exp_year' => $this->request->exp_year,
            'cvc' => $this->request->cvc,
        ];
    }
}
