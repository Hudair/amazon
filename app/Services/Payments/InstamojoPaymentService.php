<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use Instamojo\Instamojo;

class InstamojoPaymentService extends PaymentService
{
    public $instamojoReguest;
    public $redirectUrl;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function setAmount($amount)
    {
        $this->amount = number_format($amount, 2, '.', '');

        return $this;
    }

    public function setConfig()
    {
        // Get the vendor configs
        if ($this->receiver == 'merchant') {
            $vendorConfig = $this->order->shop->config->instamojo;

            $api_key = $vendorConfig->api_key;
            $auth_token = $vendorConfig->auth_token;
            $this->sandbox = $vendorConfig->sandbox;
        } else {
            $api_key = config('services.instamojo.api_key');
            $auth_token = config('services.instamojo.auth_token');
            $this->sandbox = config('services.instamojo.sandbox');
        }

        $this->instamojoReguest = new Instamojo(
            $api_key,
            $auth_token,
            $this->sandbox == 1 ? 'https://test.instamojo.com/api/1.1/' : 'https://instamojo.com/api/1.1/'
        );

        if ($this->order) {
            $paymentMethod = is_array($this->order) ? $this->order[0]->paymentMethod : $this->order->paymentMethod;

            $this->redirectUrl = route('payment.success', ['gateway' => $paymentMethod->code, 'order' => $this->getOrderId()]);
        } else {
            $this->redirectUrl = route('wallet.deposit.paypal.success');
        }

        return $this;
    }

    public function charge()
    {
        try {
            $response = $this->instamojoReguest
                ->paymentRequestCreate([
                    'purpose' => $this->description,
                    'amount' => $this->amount,
                    'buyer_name' => $this->payee ? $this->payee->getName() : $this->request->address_title,
                    'email' =>  $this->payee ? $this->payee->email : $this->request->email,
                    'phone' => $this->payee ? '' : $this->request->phone,
                    'redirect_url' => $this->redirectUrl,
                    'send_email' => true,
                ]);
        } catch (Exception $e) {
            return $e;
        }

        return redirect()->to($response['longurl']);
    }
}
