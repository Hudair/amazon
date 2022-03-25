<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateInventoryRequest extends Request
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
        if (! $this->input('key_features')) {
            $this->merge(['key_features' => null]);
        }
        if (! $this->input('linked_items')) {
            $this->merge(['linked_items' => null]);
        }

        $id = $this->route('inventory'); //Current model ID

        $rules = [
            'sku' => 'required|composite_unique:inventories,sku, '.$id,
            'title' => 'required',
            'sale_price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'available_from' => 'nullable|date',
            'offer_start' => 'nullable|date|required_with:offer_price',
            'offer_end' => 'nullable|date|required_with:offer_price|after:offer_start',
            'slug' => 'required|alpha_dash|composite_unique:inventories,slug, '.$id,
            'image' => 'mimes:jpg,jpeg,png,gif',
        ];

        if (is_incevio_package_loaded('pharmacy')) {
            $expiry_date_required = get_from_option_table('pharmacy_expiry_date_required', 1);

            $rules['expiry_date'] = (bool) $expiry_date_required ? 'required|date' : 'nullable|date';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required_with.required' => trans('validation.offer_start_required'),
            'offer_start.after_or_equal' => trans('validation.offer_start_after'),
            'required_with.required' => trans('validation.offer_end_required'),
            'offer_end.after' => trans('validation.offer_end_after'),
        ];
    }
}
