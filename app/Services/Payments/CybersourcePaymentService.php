<?php

namespace App\Services\Payments;

use CybersourcePayments;
use Illuminate\Http\Request;
use Incevio\Cybersource\CybersourceSDK\ApiException;

class CybersourcePaymentService extends PaymentService
{
    public $card;
    public $billingAddress;
    public $amountObj;
    public $refference;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function charge()
    {
        try {
            $response = CybersourcePayments::processPayment($this->refference, $this->amountObj, $this->billingAddress, $this->card, false);

            if ($response[0]['status'] == 'AUTHORIZED') {
                $this->status = self::STATUS_PAID;
            } else {
                $this->status = self::STATUS_ERROR;
            }
        } catch (\Exception $e) {
            throw new ApiException($response[0]['errorInformation']);
        }

        return $this;
    }

    public function setConfig()
    {
        if ($this->receiver == 'merchant') {
            // Get the vendor configs
            $vendorConfig = $this->order->shop->config->cybersource;

            $merchant_id = $vendorConfig->merchant_id;
            $api_key_id = $vendorConfig->api_key_id;
            $secret = $vendorConfig->secret;
            $this->sandbox = $vendorConfig->sandbox;
        } else {
            $merchant_id = config('services.cybersource.merchant_id');
            $api_key_id = config('services.cybersource.api_key_id');
            $secret = config('services.cybersource.secret');
            $this->sandbox = config('services.cybersource.sandbox');
        }

        config()->set('cybersource_config.authType', 'http_signature');
        config()->set('cybersource_config.merchantID', $merchant_id);
        config()->set('cybersource_config.apiKeyID', $api_key_id);
        config()->set('cybersource_config.secretKey', $secret);
        config()->set('cybersource_config.mode', 'cyberSource.environment.' . $this->sandbox ? 'SANDBOX' : 'PRODUCTION');

        $this->amountObj = [
            'totalAmount' => get_formated_decimal($this->amount, false, 2),
            'currency'    => $this->currency_code,
        ];

        if ($this->order) {
            $this->refference = [
                'code' => trans('app.purchase_from', ['marketplace' => get_platform_title()]),
            ];
        }

        $this->setCard();

        $this->setBillingAddress();

        return $this;
    }

    private function setBillingAddress()
    {
        $address = null;

        if ($this->payee) {
            $address = $this->payee->billingAddress ?? $this->payee->address;
        }

        $country_id = $address ? $address->country_id : $this->request->country_id;
        $state_id = $address && $address->state ? $address->state_id : $this->request->state_id;

        $name = explode(' ', $this->request->cardholder_name);
        $fname = $name[0];
        $lname = count($name) > 1 ? end($name) : $fname;

        $locality = ($address && $address->city) ? $address->city : $this->request->city;

        $this->billingAddress = [
            'firstName'          => $fname,
            'lastName'           => $lname,
            'address1'           => $address ? $address->address_line_1 : $this->request->address_line_1,
            'address2'           => $address ? $address->address_line_2 : $this->request->address_line_2,
            'postalCode'         => $address ? $address->zip_code : $this->request->zip_code,
            'locality'           => $locality ?? get_value_from($state_id, 'states', 'name'),
            'country'            => get_value_from($country_id, 'countries', 'iso_code'),
            'administrativeArea' => $state_id ? get_value_from($state_id, 'states', 'iso_code') : '',
            'phoneNumber'        => $address ? $address->phone : $this->request->phone,
            'email'              => $this->payee ? $this->payee->email : $this->request->email,
        ];

        return $this;
    }

    private function setCard()
    {
        $this->card = [
            'number'          => $this->request->cnumber,
            'securityCode'    => $this->request->ccode,
            'expirationMonth' => $this->request->card_expiry_month,
            'expirationYear'  => $this->request->card_expiry_year,
        ];

        return $this;
    }
}
