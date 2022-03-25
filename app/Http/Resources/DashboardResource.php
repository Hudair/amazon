<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
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
            'nice_name' => $this->nice_name,
            'dob' => $this->dob ? date('F j, Y', strtotime($this->dob)) : null,
            'sex' => trans($this->sex),
            'description' => $this->description,
            'active' => $this->active,
            'accepts_marketing' => $this->accepts_marketing,
            'orders_count' => $this->orders_count,
            'wishlists_count' => $this->wishlists_count,
            'disputes_count' => $this->disputes_count,
            'coupons_count' => $this->coupons_count,
            'member_since' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'avatar' => get_avatar_src($this, 'small'),
            // 'avatar' => (new ImageResource($this->image))->size('small'),
        ];
    }
}
