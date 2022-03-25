<?php

namespace App\Http\Requests\Validations;

use App\Models\Customer;
use App\Http\Requests\Request;

class OrderCancellationRequest extends Request
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
        }

        if ($this->user()->isFromPlatform()) {
            return true;
        }

        // If the cancellation created by vendor then cancellation_fee fee can not be present
        return $this->route('order')->shop_id == $this->user()->merchantId() && ! $this->has('cancellation_fee');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Request::merge([
            'shop_id' => $this->route('order')->shop_id,
            'customer_id' => $this->route('order')->customer_id,
        ]);

        if ($this->action == 'return') {
            Request::merge(['return_goods' => 1]);
        }

        // When customer cancel
        if ($this->user() instanceof Customer) {
            return [
                'cancellation_reason_id' => 'required|integer',
                'items' => 'required_without:all_items|array',
            ];
        }

        // When admin cancel
        if ($this->user()->isFromPlatform()) {
            return [
                'cancellation_fee' => 'required|numeric|min:0',
            ];
        }

        // When vendor cancel
        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cancellation_reason_id.required' => trans('theme.cancellation_reason_required'),
            'items.required_without' => trans('theme.select_cancel_items_required'),
        ];
    }
}
