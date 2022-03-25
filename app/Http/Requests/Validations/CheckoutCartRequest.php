<?php

namespace App\Http\Requests\Validations;

use App\Models\Address;
use App\Models\Customer;
use App\Common\CanCreateStripeCustomer;
use App\Http\Requests\Request;
use App\Services\NewCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Factory as ValidationFactory;

class CheckoutCartRequest extends Request
{
    use CanCreateStripeCustomer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($cart = $this->route('cart')) {
            return crosscheckCartOwnership($this, $cart);
        }

        // When the using the one checkout plugin
        if ($this->route()->getName() == config('checkout.routes.place_order')) {
            return true;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if (
            $this->has('email')
            && $this->has('create-account')
            && $this->has('password')
            && !Customer::where('email', $this->input('email'))->exists()
        ) {
            try {
                $customer = (new NewCustomer)->save($this);
            } catch (\Exception $e) {
                Log::error($e);
                $rules['address_validator'] = 'integer';
            }

            // Logout the customer if already logged in
            if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
                Auth::logout();
            }

            // Login the customer
            if ($this->wantsJson()) {
                $token = $customer->generateToken();

                // Update the cart with the new customer
                if ($cart = $this->route('cart')) {
                    $cart->customer_id = $customer->id;
                    $cart->save();
                }

                // Update the request params
                $this->merge(['api_token' => $token]);
            } else {
                Auth::guard('customer')->login($customer);
            }

            // Update the request params
            $this->merge(['customer_id' => $customer->id]);
        }

        // Create Stripe Customer for future use
        if (
            $this->checkAuth() &&
            $this->has('remember_the_card') &&
            $this->input('payment_method') == 'stripe'
        ) {
            // Set Payee to use in payment gateway
            $this->merge(['payee' => $this->createStripeCustomer()]);
        }

        // Get payment method id
        if ($this->payment_method && !$this->payment_method_id) {
            $code = $this->payment_method == 'saved_card' ? 'stripe' : $this->payment_method;

            // Set payment method id
            $this->merge([
                'payment_method_id' => get_id_of_model('payment_methods', 'code', $code)
            ]);
        }

        // Get shipping address
        if (is_numeric($this->ship_to)) {
            $address = Address::find($this->ship_to)->toHtml('<br/>', false);
        } else {
            $address = get_address_str_from_request_data($this);
        }

        // Set address and device_id
        $this->merge([
            'shipping_address' => $address,
            'device_id' => $this->device_id ? str_replace('"', '', $this->device_id) : null,
        ]);

        // Common rules for order
        $rules['agree'] = 'required';

        if (!$this->checkAuth()) {
            $unique_ck = 'required|email|max:255';
            $password = 'bail|nullable|min:6';

            if (!isset($customer) && $this->has('create-account')) {
                $unique_ck .= '|unique:customers';
                $password .= '|required_with:create-account|confirmed';
            }

            $rules['email'] = $unique_ck;
            $rules['password'] = $password;
        }

        if ('saved_card' != $this->payment_method) {
            $rules['payment_method'] = ['required', 'exists:payment_methods,code,enabled,1'];
        }

        // Pharmacy plugin rules
        if (
            is_incevio_package_loaded('pharmacy') &&
            get_from_option_table('pharmacy_prescription_required', 1)
        ) {
            $mimes = 'mimes:jpg,jpeg,png,pdf';

            // Request from API
            if ($this->is('api/*')) {
                $rules['prescription'] = 'required';
            } else {
                $rules['prescription'] = 'required|' . $mimes;
            }
        }

        return $rules;
    }

    /** Check if the user is regitered customer */
    private function checkAuth()
    {
        return Auth::guard('customer')->check() || Auth::guard('api')->check();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'address_validator.integer' => trans('theme.invalid_address'),
        ];
    }
}
