<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemConfigResource extends JsonResource
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
      "maintenance_mode" => (bool) $this->maintenance_mode,
      "install_verion" => $this->install_verion,
      "name" => $this->name,
      "slogan" => $this->slogan,
      "legal_name" => $this->legal_name,
      "email" => $this->email,
      "worldwide_business_area" => (bool) $this->worldwide_business_area,
      "timezone_id" => $this->timezone_id,
      "currency_id" => $this->currency_id,
      "default_language" => $this->default_language,
      "ask_customer_for_email_subscription" => (bool) $this->ask_customer_for_email_subscription,
      "can_cancel_order_within" => $this->can_cancel_order_within,
      "support_phone" => $this->support_phone,
      "support_phone_toll_free" => $this->support_phone_toll_free,
      "support_email" => $this->support_email,
      "facebook_link" => $this->facebook_link,
      "google_plus_link" => $this->google_plus_link,
      "twitter_link" => $this->twitter_link,
      "pinterest_link" => $this->pinterest_link,
      "instagram_link" => $this->instagram_link,
      "youtube_link" => $this->youtube_link,
      "length_unit" => $this->length_unit,
      "weight_unit" => $this->weight_unit,
      "valume_unit" => $this->valume_unit,
      "decimals" => $this->decimals,
      "show_currency_symbol" => (bool) $this->show_currency_symbol,
      "show_space_after_symbol" => (bool) $this->show_space_after_symbol,
      "max_img_size_limit_kb" => $this->max_img_size_limit_kb,
      "show_item_conditions" => (bool) $this->show_item_conditions,
      "address_default_country" => $this->address_default_country,
      "address_default_state" => $this->address_default_state,
      "show_address_title" => (bool) $this->show_address_title,
      "address_show_country" => (bool) $this->address_show_country,
      "address_show_map" => (bool) $this->address_show_map,
      "allow_guest_checkout" => (bool) $this->allow_guest_checkout,
      "enable_chat" => (bool) $this->enable_chat,
      "currency" => [
        'name' => $this->currency['name'],
        'iso_code' => $this->currency['iso_code'],
        "symbol" => $this->currency['symbol'],
        "symbol_first" => (bool) $this->currency['symbol_first'],
        "subunit" => $this->currency['subunit'],
        "decimal_mark" => $this->currency['decimal_mark'],
        "thousands_separator" => $this->currency['thousands_separator'],
      ],
    ];
  }
}
