<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $decimal = config('system_settings.decimals', 2);

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id ? (int) $this->customer_id : null,
            'ip_address' => $this->ip_address,
            'ship_to' => $this->ship_to ? (int) $this->ship_to : null,
            'ship_to_country_id' => $this->ship_to_country_id ? (int) $this->ship_to_country_id : null,
            'ship_to_state_id' => $this->ship_to_state_id ? (int) $this->ship_to_state_id : null,
            'shipping_zone_id' => $this->shipping_zone_id ? (int) $this->shipping_zone_id : null,
            'shipping_option_id' => $this->shipping_rate_id ? (int) $this->shipping_rate_id : null,
            'shipping_address' => strip_tags(str_replace('<br/>', ', ', $this->shipping_address)),
            'billing_address' => strip_tags(str_replace('<br/>', ', ', $this->billing_address)),
            'shipping_weight' => get_formated_weight($this->shipping_weight),
            'packaging_id' => $this->packaging_id ? (int) $this->packaging_id : null,
            'coupon' => $this->coupon_id ? [
                'id' => $this->coupon->id,
                'name' => $this->coupon->name,
                'code' => $this->coupon->code,
                'amount' => get_formated_currency($this->discount),
                'amount_raw' => $this->discount,
                'label' => trans('app.coupon_applied', ['coupon' => $this->coupon->name]),
            ] : null,
            'total' => get_formated_currency($this->total, $decimal),
            'total_raw' => $this->total,
            'shipping' => get_formated_currency($this->shipping, $decimal),
            'shipping_raw' => $this->shipping,
            'packaging' => get_formated_currency($this->packaging, $decimal),
            'packaging_raw' => $this->packaging,
            'handling' => get_formated_currency($this->handling, $decimal),
            'handling_raw' => $this->handling,
            'taxrate' => get_formated_decimal($this->taxrate, true, $decimal) . '%',
            'taxes' => get_formated_currency($this->taxes, $decimal),
            'taxes_raw' => $this->taxes,
            'discount' => get_formated_currency($this->discount, $decimal),
            'discount_raw' => $this->discount,
            'grand_total' => get_formated_currency($this->grand_total, $decimal),
            'grand_total_raw' => $this->grand_total,
            'label' => $this->getLabelText(),
            'shop' => new ShopLightResource($this->shop),
            'items' => OrderItemResource::collection($this->inventories),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        if ($this->ship_to_country_id) {
            $country = DB::table('countries')->select('id', 'iso_code')
                ->where('id', $this->ship_to_country_id)->first();
        }

        if (isset($country)) {
            $country_code =  $country->iso_code;
        } else {
            $geoip = geoip(request()->ip());
            $country_code =  $geoip->iso_code;
        }

        return [
            'meta' => [
                // 'currency_symbol' => get_currency_symbol(),
                'currency' => get_system_currency(),
                'country_code' => $country_code,
            ],
        ];
    }
}
