<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SelfPasswordUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('customer')->check() || Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Auth::guard('customer')->check()) {
            $password = Auth::guard('customer')->user()->password;
        } elseif (Auth::guard('api')->check()) {
            $password = Auth::guard('api')->user()->password;
        }

        Validator::extend('check_current_password', function ($attribute, $value, $parameters) use ($password) {
            return Hash::check($value, $password);
        });

        $rules = [];

        // Current password is required if it set
        // if (Auth::guard('customer')->user()->password)
        $rules['current_password'] = 'required|check_current_password';

        $rules['password'] = 'required|confirmed|min:6';
        $rules['password_confirmation'] = 'required';

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
            'current_password.check_current_password' => trans('theme.validation.incorrect_current_password'),
        ];
    }
}
