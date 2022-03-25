<?php

namespace App\Http\Requests\Validations;

use App\Models\Customer;
use App\Http\Requests\Request;

class ReplyMyMessageRequest extends Request
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
        }

        return $this->route('message')->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->user() instanceof Customer) {
            Request::merge(['customer_id' => $this->user()->id]);
        } //Set customer_id
        else {
            Request::merge(['user_id' => $this->user()->id]);
        } //Set user_id

        return [
            'reply' => 'required|max:500',
        ];
    }
}
