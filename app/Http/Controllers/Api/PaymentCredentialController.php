<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Validations\ApiPaymentCredentialRequest;

class PaymentCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function credential(ApiPaymentCredentialRequest $request, $payment_method)
    {
        $shop = null;

        // When vendor get paid directly
        if ($request->has('cart_id') && vendor_get_paid_directly()) {
            $cart = Cart::findOrFail($request->get('cart_id'));
            $shop = $cart->shop_id;
        }

        $credential = get_payment_config_info($payment_method, $shop);

        /**
         * openssl_encrypt(
         *  string $data,
         *  string $cipher_algo,
         *  string $passphrase,
         *  int $options = 0,
         *  string $iv = "",
         *  string &$tag = null,
         *  string $aad = "",
         *  int $tag_length = 16
         * ): string|false
         */

        $encrypted_credential = openssl_encrypt(
            json_encode($credential),
            Config::get('app.cipher'),
            Config::get('app.zcart_encryption_key'),
            0,
            Config::get('app.zcart_encryption_iv')
        );

        return [
            'data' => $credential ? $encrypted_credential : null,
            'raw' => $credential
        ];
    }
}
