<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use App\Exceptions\PaymentConfigInvalid;
use Yabacon\Paystack\Exception\ApiException;

class PaystackPaymentService extends PaymentService
{
    public $tranx;
    public $redirectUrl;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function charge()
    {
        return redirect()->to($this->tranx->data->authorization_url);
    }

    public function setConfig()
    {
        $this->setCallbackUrl();

        if ($this->receiver == 'merchant') {
            // Get the vendor configs
            $vendorConfig = $this->order->shop->config->paystack;

            $secret = $vendorConfig->secret;
            $this->sandbox = $vendorConfig->sandbox;
        } else {
            $secret = config('services.paystack.secret');
            $this->sandbox = config('services.paystack.sandbox');
        }

        $meta = [];
        $quantity = 1;

        if ($this->order) {
            if (is_array($this->order)) {
                $quantity = array_sum(array_column($this->order, 'quantity'));
            } else {
                $quantity = $this->order->quantity;

                $meta = [
                    'order_number' => $this->order->order_number,
                    'custom_fields' => [
                        [
                            'display_name' => "Order Number",
                            'variable_name' => "order_number",
                            'value' => $this->order->order_number
                        ], [
                            'display_name' => "Shipping Address",
                            'variable_name' => "shipping_address",
                            'value' => $this->order->shipping_address
                        ]
                    ]
                ];
            }
        }

        $data = [
            'email' => $this->request->email ?? $this->payee->email,
            'amount' => get_cent_from_doller($this->amount),
            'quantity' => $quantity,
            'orderID' => $this->order ? $this->getOrderId() : null,
            'callback_url' => $this->redirectUrl,
            'metadata' => json_encode($meta),
        ];

        $paystack = new \Yabacon\Paystack($secret);

        try {
            $tranx = $paystack->transaction->initialize($data);
        } catch (ApiException $e) {
            // print_r($e->getResponseObject());
            // die($e->getMessage());
            throw new PaymentConfigInvalid($e->getMessage());
        }

        // if (!$tranx->status) {
        //     throw new ApiException;
        // }

        $this->tranx = $tranx;

        return $this;
    }

    private function setCallbackUrl()
    {
        if (!$this->order) {
            $this->redirectUrl = route('wallet.deposit.paystack.success');

            return $this;
        }

        $paymentMethod = is_array($this->order) ?
            $this->order[0]->paymentMethod : $this->order->paymentMethod;

        $this->redirectUrl = route('payment.success', ['gateway' => $paymentMethod->code, 'order' => $this->getOrderId()]);
    }

    /**
     * Verify Payment
     * @param $reference
     * @return mixed
     */
    public function verify($reference)
    {
        $secret = config('services.paystack.secret');
        $paystack = new \Yabacon\Paystack($secret);

        return $paystack->transaction->verify(['reference' => $reference]);
    }

    /**
     * Verify Paid Payment
     */
    public function verifyPaidPayment()
    {
        $payment_meta = json_decode($this->request->input('payment_meta'));

        if ($payment_meta->reference) {
            $response = $this->verify($payment_meta->reference);

            if ($response->status) {
                $this->status = self::STATUS_PAID;
            } else {
                $this->status = self::STATUS_ERROR;
            }
        }

        return $this;
    }
}
