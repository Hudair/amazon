<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use App\Models\Order;

class InitiateRefundRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order = Order::findOrFail($this->order_id);

        if (! $order) {
            return false;
        }

        $this->merge(['shop_id' => $order->shop_id]); //Set shop_id

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'order_id' => 'required',
           'amount' => 'required|numeric',
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
            'order_id.required' => trans('validation.refund_order_id_required'),
        ];
    }
}
