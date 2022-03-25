<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class SaveChatConversationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If the user is customer allow chat
        if ($this->customer_id()) {
            return true;
        }

        $user = Auth::guard('web')->check() ? Auth::guard('web')->user() : null;

        if ($user && $user->merchantId() == $this->chat->shop_id) {
            return true;
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
        $this->merge([
            'customer_id' => $this->customer_id(),
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : null,
        ]); //Set customer_id and user_id

        return [
            // 'message' => 'required',
        ];
    }

    /**
     * Return customer id
     */
    private function customer_id()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user()->id;
        }

        if (Auth::guard('api')->check()) {
            return Auth::guard('api')->user()->id;
        }

        return null;
    }
}
