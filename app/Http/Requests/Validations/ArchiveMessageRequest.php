<?php

namespace App\Http\Requests\Validations;

use App\Models\Customer;
use App\Http\Requests\Request;

class ArchiveMessageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user() instanceof Customer) {
            return $this->route('message')->customer_id == $this->user()->id;
        } else {
            return $this->route('message')->shop_id == $this->user()->merchantId();
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
