<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ManufacturerLightResource extends JsonResource
{
    // protected $listings;

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
            'slug' => $this->slug,
            'description' => Str::limit($this->description, 100),
            // 'origin' => $this->country->name,
            // 'listing_count' => $this->inventories_count,
            'available_from' => date('F j, Y', strtotime($this->created_at)),
            'image' => get_logo_url($this, 'medium'),
        ];
    }
}
