<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UpdateDeliveryBoyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->deliveryboy->shop_id == Auth::user()->merchantId();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'nice_name' => 'nullable|string|max:50',
            'email' =>  'required|email|max:255|composite_unique:delivery_boys, ' . $this->deliveryboy->id,
            'dob' => 'nullable|date',
            'status' => 'required',
            'phone_number' => 'required|string|max:50',
            'image' => 'mimes:jpg,jpeg,png',
        ];
    }
}
