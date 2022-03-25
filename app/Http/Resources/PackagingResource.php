<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagingResource extends JsonResource
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
            'cost' => get_formated_currency($this->cost, config('system_settings.decimals', 2)),
            'cost_raw' => $this->cost,
            'height' => $this->height ?? null,
            'width' => $this->width ?? null,
            'depth' => $this->depth ?? null,
            'default' => $this->default ?? null,
        ];
    }
}
