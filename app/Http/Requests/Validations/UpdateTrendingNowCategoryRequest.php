<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateTrendingNowCategoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        incevioAutoloadHelpers(getMysqliConnection());
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trending_categories' => 'nullable|array',
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
            // 'featured_categories.required' => trans('validation.category_list_required'),
        ];
    }
}
