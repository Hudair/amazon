<?php

namespace App\Http\Requests\Validations;

use App\Models\Customer;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateDisputeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user() instanceof Customer) {
            return $this->route('order')->customer_id == $this->user()->id;
        } else {
            return $this->route('order')->shop_id == $this->user()->merchantId();
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $order = $this->route('order');

        Request::merge([
            'order_id' => $order->id,
            'shop_id' => $order->shop_id,
            'customer_id' => $order->customer_id,
        ]);

        return [
            'dispute_type_id' => 'required',
            'order_received' => 'required',
            'description' => 'required',
            'product_id' => Rule::requiredIf($this->order_received == 1),
            'refund_amount' => 'required|numeric|max:'.$order->grand_total,
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
            'dispute_type_id.required' => trans('theme.validation.dispute_type_id_required'),
            'product_id.required_with' => trans('theme.validation.dispute_product_id_required_with'),
        ];
    }
}
