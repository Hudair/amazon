<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'slug' => $this->slug,
            'gtin_type' => $this->gtin_type,
            'gtin' => $this->gtin,
            'description' => $this->description,
            'brand' => $this->manufacturer->name,
            'brand_slug' => $this->manufacturer->slug,
            'image' => get_catalog_featured_img_src($this, 'small'),
            // 'image' => (new ImageResource($this->featureImage))->size('small'),
            'listings' => ListingResource::collection($this->inventories),
        ];
    }
}
