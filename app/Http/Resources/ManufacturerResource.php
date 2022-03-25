<?php

namespace App\Http\Resources;

use App\Helpers\ListHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerResource extends JsonResource
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
            'url' => $this->url,
            'description' => $this->description,
            'origin' => $this->country->name,
            'listing_count' => $this->inventories_count,
            'available_from' => date('F j, Y', strtotime($this->created_at)),
            'image' => get_logo_url($this, 'small'),
            'cover_image' => get_cover_img_src($this, 'brand'),
            // 'listings' => $this->listings,
            // 'listings' => ListingResource::collection($listings),
        ];
    }

    // public static function collection($resource)
    // {
    //     return new ManufacturerResourceCollection($resource);
    // }

    // public function listings($items)
    // {
    //     $this->listings = $items;

    //     return $this;
    // }
}
