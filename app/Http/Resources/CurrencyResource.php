<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
      'name' => $this->name,
      'iso_code' => $this->iso_code,
      "iso_numeric" => $this->iso_numeric,
      "symbol" => $this->symbol,
      "disambiguate_symbol" => $this->disambiguate_symbol,
      "subunit" => $this->subunit,
      "subunit_to_unit" => $this->subunit_to_unit,
      "html_entity" => $this->html_entity,
      "decimal_mark" => $this->decimal_mark,
      "thousands_separator" => $this->thousands_separator,
      "smallest_denomination" => $this->smallest_denomination,
      "symbol_first" => $this->symbol_first,
      "priority" => $this->priority,
    ];
  }
}
