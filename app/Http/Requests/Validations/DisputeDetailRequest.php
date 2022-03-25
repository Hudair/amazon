<?php

namespace App\Http\Requests\Validations;

use App\Models\Customer;
use App\Http\Requests\Request;

class DisputeDetailRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user() instanceof Customer) {
            return $this->route('dispute')->customer_id == $this->user()->id;
        }

        return $this->route('dispute')->shop_id == $this->user()->merchantId();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
