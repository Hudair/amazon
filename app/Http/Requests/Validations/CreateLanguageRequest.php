<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class CreateLanguageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language' => 'required',
            'code' => 'required|unique:languages',
            'php_locale_code' => 'required|unique:languages',
            'active' => 'required',
            'order' => 'nullable|numeric',
        ];
    }
}
