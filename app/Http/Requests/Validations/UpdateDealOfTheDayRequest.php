<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateDealOfTheDayRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        incevioAutoloadHelpers(getMysqliConnection());
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
            'item_id' => 'nullable|numeric',
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
            'item_id.required' => trans('validation.select_the_item'),
        ];
    }
}
