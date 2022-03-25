<?php

namespace Incevio\Package\DynamicCommission\Http\Requests;

use Auth;
use App\Http\Requests\Request;

class DynamicCommissionRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isFromPlatform();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //"milestones.*"    => "required|array|min:2",
            "milestones.amounts.*"    => "required|numeric|min:0",
            "milestones.commissions.*"    => "required|numeric|min:0|max:100",
        ];
    }
}