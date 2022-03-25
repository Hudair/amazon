<?php

namespace Incevio\Package\Wallet\Http\Requests;

use App\Http\Requests\Request;

class WithdrawalActionsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ('admin.wallet.payout.decline' == $this->route()->getName()) {
            return [];
        }

        return [
            'fee' => 'required|numeric'
        ];
    }
}
