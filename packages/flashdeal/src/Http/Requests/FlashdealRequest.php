<?php

namespace Incevio\Package\flashdeal\Http\Requests;

use App\Http\Requests\Request;

class FlashdealRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'listings' => 'required_without:featured',
            'featured' => 'required_without:listings'
        ];
    }

}