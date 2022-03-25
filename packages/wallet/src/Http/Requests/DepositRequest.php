<?php

namespace Incevio\Package\Wallet\Http\Requests;

use Auth;
use App\Http\Requests\Request;
use App\Common\CanCreateStripeCustomer;

class DepositRequest extends Request
{
    use CanCreateStripeCustomer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::guard('web')->check() && Auth::user()->isMerchant()) {
            return true;
        }

        return Auth::guard('customer')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Create Stripe Customer for future use
        if ($this->has('remember_the_card') && $this->input('payment_method') == 'stripe') {
            $this->merge([
                'payee' => $this->createStripeCustomer(),
            ]);
        }

        return [
           'amount' => 'required|numeric|min:1',
           'payment_method' => $this->input('payment_method') == 'saved_card' ? '' : 'required|exists:payment_methods,code',
        ];
    }
}