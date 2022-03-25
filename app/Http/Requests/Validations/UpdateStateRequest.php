<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateStateRequest extends Request
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
        $rules = [
           'name' => 'required|string',
           'active' => 'required',
        ];

        $state = $this->route('state');

        if (! $state->iso_code) {
            $rules['iso_code'] = 'required|size:3|unique:states';
        }

        return $rules;
    }
}
