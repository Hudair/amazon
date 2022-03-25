<?php

namespace Incevio\Package\Wallet\Http\Requests;

use App\Http\Requests\Request;

class WithdrawalRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->isMerchant();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'amount' => 'required|numeric|min:' . get_min_withdrawal_limit() . '|max:' . \Auth::user()->shop->balance,
        ];
    }

   /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'amount.min' => trans('wallet::lang.composite_unique'),
        ];
    }
}
