<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ContactSellerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('customer')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'subject' => 'required|max:200',
            'message' => 'required|max:500',
        ];

        if (config('services.recaptcha.key')) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
    }
}
