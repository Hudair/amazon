<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;
use App\Models\ShippingZone;
use Illuminate\Support\Arr;

class CreateShippingZoneRequest extends Request
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
        $shop_id = Request::user()->merchantId(); //Get current user's shop_id
        Request::merge(['shop_id' => $shop_id]); //Set shop_id

        if ($this->has('rest_of_the_world')) {
            Request::merge(['state_ids' => [], 'country_ids' => []]);
        } elseif ($this->has('country_ids')) {
            // All state ids of select countries
            $all_state_ids = \DB::table('states')
                ->whereIn('country_id', $this->input('country_ids'))
                ->where('active', 1)->pluck('id')->toArray();

            // Skip state ids that are already in other shipping zones
            $zones = ShippingZone::where('shop_id', $shop_id)->pluck('state_ids')->toArray();
            $existing_ids = Arr::flatten(array_filter($zones));

            Request::merge(['state_ids' => array_diff($all_state_ids, $existing_ids)]);
        }

        return [
            'name' => 'bail|required|composite_unique:shipping_zones,shop_id:'.$shop_id,
            'tax_id' => 'required',
            'country_ids' => 'required_unless:rest_of_the_world,1',
            'rest_of_the_world' => 'bail|sometimes|nullable|composite_unique:shipping_zones,shop_id:'.$shop_id,
            'active' => 'required|boolean',
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
            'country_ids.required_unless' => trans('validation.shipping_zone_country_ids_required'),
            'tax_id.required' => trans('validation.shipping_zone_tax_id_required'),
            'rest_of_the_world.composite_unique' => trans('validation.rest_of_the_world_composite_unique'),
        ];
    }
}
