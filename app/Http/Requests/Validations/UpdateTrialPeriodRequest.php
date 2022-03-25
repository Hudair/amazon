<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateTrialPeriodRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Request::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trial_ends_at' => 'required|date',
        ];
    }
}
