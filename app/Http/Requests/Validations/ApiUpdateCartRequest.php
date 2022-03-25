<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class ApiUpdateCartRequest extends Request
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
            'quantity' => 'nullable|int',
            'item' => 'required_with:quantity|int',
            'packaging_id' => 'nullable|int',
            'shipping_zone_id' => 'nullable|int',
            'shipping_option_id' => 'nullable|int',
            // 'shipping_option_id' => 'required_with:shipping_zone_id',
        ];
    }
}
