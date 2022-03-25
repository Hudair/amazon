<?php

namespace App\Http\Requests\Validations;

use App\Models\EmailTemplate;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ReplyMessageRequest extends Request
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
        $extra['user_id'] = Auth::id(); //Set user_id

        if (Request::has('email_template_id')) {
            $extra['reply'] = EmailTemplate::find(Request::input('email_template_id'))->body;
        }

        Request::merge($extra); //Set extra data

        return [
            'reply' => 'required_without:email_template_id',
            'email_template_id' => 'required_without:reply',
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
            'reply.required_without' => trans('validation.reply_required_without'),
            'email_template_id.required_without' => trans('validation.template_id_required_without'),
        ];
    }
}
