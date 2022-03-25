<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateSliderRequest extends Request
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
            'title' => 'max:255',
            'sub_title' => 'max:255',
            'images.feature' => 'mimes:jpg,jpeg,png,gif',
            'images.mobile' => 'mimes:jpg,jpeg,png,gif',
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
            'images.feature.required' => trans('validation.slider_image_required'),
        ];
    }
}
