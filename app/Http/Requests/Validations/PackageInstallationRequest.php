<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use App\Rules\License;

class PackageInstallationRequest extends Request
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
            'license_key' => ['bail', 'required', 'alpha_dash', new License],
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
            'license_key.alpha_dash' => trans('app.invalid_license_key'),
        ];
    }
}
