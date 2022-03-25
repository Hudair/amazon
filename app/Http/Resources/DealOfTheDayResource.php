<?php

namespace App\Http\Resources;

// use App\Helpers\ListHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class DealOfTheDayResource extends JsonResource
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
      'title' => $this->title,
      'brand' => $this->brand,
      // 'sku' => $this->sku,
      'condition' => $this->condition,
      // 'condition_note' => $this->condition_note,
      'description' => Str::limit($this->description, 100),
      'key_features' => $this->key_features ? unserialize($this->key_features) : [],
      'stock_quantity' => $this->stock_quantity,
      'has_offer' => $this->hasOffer(),
      'raw_price' => $this->current_sale_price(),
      'currency' => get_system_currency(),
      'currency_symbol' => get_currency_symbol(),
      'price' => get_formated_currency($this->sale_price, config('system_settings.decimals', 2)),
      'offer_price' => $this->hasOffer() ? get_formated_currency($this->offer_price, config('system_settings.decimals', 2)) : null,
      'discount' => $this->hasOffer() ? trans('theme.percent_off', ['value' => $this->discount_percentage()]) : null,
      'offer_start' => $this->hasOffer() ? (string) $this->offer_start : null,
      'offer_end' => $this->hasOffer() ? (string) $this->offer_end : null,
      // 'attributes' => AttributeDryResource::collection($this->whenLoaded('attributeValues')),
      'images' => ImageResource::collection($this->whenLoaded('images')),
      'free_shipping' => $this->free_shipping,
      'stuff_pick' => $this->stuff_pick,
      'rating' => $this->rating(),
      'feedbacks_count' => $this->rating() ? $this->avgFeedback->count : 0,
      'labels' => $this->getLabels(),
    ];
  }
}
