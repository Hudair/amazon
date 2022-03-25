<?php

namespace App\Http\Requests\Validations;

use Illuminate\Foundation\Http\FormRequest;

class CustomCssRequest extends FormRequest
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
            'theme_custom_css' => 'nullable|valid_css',
        ];
    }
}
