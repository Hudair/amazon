<?php

namespace App\Http\Requests\Validations;

use App\Models\Message;
use App\Models\Order;
use App\Models\EmailTemplate;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;


class CreateMessageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $shop_id = Auth::user()->merchantId();

        //Set some extra values
        $this->merge([
            'shop_id' => $shop_id,
            'user_id' => Auth::user()->id,
            'label' => $this->has('draft') ? Message::LABEL_DRAFT : Message::LABEL_SENT,
            'status' => Message::STATUS_READ,
        ]);

        if ($this->has('email_template_id')) {
            $template = EmailTemplate::find($this->input('email_template_id'));
            $this->merge([
                'subject' => $template->subject,
                'message' => $template->body,
            ]);
        }

        // If its order conversation
        if ($this->order_id) {
            $order = Order::findOrFail($this->order_id);

            if (Auth::user()->isFromMerchant() && $order->shop_id != $shop_id) {
                abort(403, trans('responses.unauthorised'));
            }

            $this->merge([
                'email' => $order->email,
                'customer_id' => $order->customer_id,
            ]);
        }

        return [
            'subject' => 'required_without:email_template_id',
            'message' => 'required_without:email_template_id',
            'email_template_id' => 'required_without_all:subject,message',
            'customer_id' => 'required_without:order_id',
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
            'subject.required_without' => trans('validation.subject_required_without'),
            'message.required_without' => trans('validation.message_required_without'),
            'email_template_id.required_without_all' => trans('validation.template_id_required_without_all'),
            'customer_id.required_without' => trans('validation.customer_required'),
        ];
    }
}
