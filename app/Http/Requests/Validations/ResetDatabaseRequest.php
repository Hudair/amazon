<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;

class ResetDatabaseRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isSuperAdmin() && Hash::check($this->password, $this->user()->password);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'do_action' =>  'required|in:RESET',
            'password' =>  'required|min:6',
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
            'do_action.required' => trans('validation.do_action_required'),
            'do_action.in' => trans('validation.do_action_invalid'),
        ];
    }
}
