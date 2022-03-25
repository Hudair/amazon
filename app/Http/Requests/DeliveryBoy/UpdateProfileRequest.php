<?php

namespace App\Http\Requests\DeliveryBoy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'first_name' => 'required',
            'last_name' => 'required',
            'nice_name' => 'nullable|string|max:50',
            'email' =>  'required|email|max:255|composite_unique:delivery_boys, ' . Auth::guard('delivery_boy-api')->id(),
            'dob' => 'nullable|date',
            'phone_number' => 'required|string|max:50',
            'image' => 'mimes:jpg,jpeg,png',
        ];
    }
}
