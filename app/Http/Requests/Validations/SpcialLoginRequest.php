<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class SpcialLoginRequest extends Request
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
        $rules = [];

        // When the reuest from API
        if ($this->expectsJson()) {
            $rules['access_token'] = 'required';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'access_token.required' => trans('api.access_token_required'),
        ];
    }
}
