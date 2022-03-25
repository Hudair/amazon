<?php

namespace App\Http\Requests\Validations;

use App\Models\User;
use App\Http\Requests\Request;

class AdminUserUpdatePasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::find($this->route('user'));

        if (!$user) {
            return false;
        }

        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' =>  'required|confirmed|min:6',
        ];
    }
}
