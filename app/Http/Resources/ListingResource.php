<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'product_id' => $this->product_id,
            'title' => $this->title,
            'condition' => $this->condition,
            // 'attributes' => AttributeLightResource::collection($this->whenLoaded('attributeValues')),
            'has_offer' => $this->hasOffer(),
            'raw_price' => $this->current_sale_price(),
            'currency' => get_system_currency(),
            'currency_symbol' => get_currency_symbol(),
            'price' => get_formated_currency($this->sale_price, config('system_settings.decimals', 2)),
            'offer_price' => $this->hasOffer() ? get_formated_currency($this->offer_price, config('system_settings.decimals', 2)) : null,
            'discount' => $this->hasOffer() ? trans('theme.percent_off', ['value' => $this->discount_percentage()]) : null,
            'offer_start' => $this->hasOffer() ? (string) $this->offer_start : null,
            'offer_end' => $this->hasOffer() ? (string) $this->offer_end : null,
            'stuff_pick' => $this->stuff_pick,
            'free_shipping' => $this->free_shipping,
            'hot_item' => $this->orders_count >= config('system.popular.hot_item.sell_count', 3) ? true : false,
            'rating' => $this->rating(),
            'feedbacks_count' => $this->rating() ? $this->avgFeedback->count : 0,
            'labels' => $this->getLabels(),
            'image' => get_inventory_img_src($this, 'medium'),
        ];
    }
}
