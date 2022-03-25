<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->getName(),
            'email' => $this->email,
            'member_since' => optional($this->created_at)->diffForHumans(),
            'active' => $this->active,
            'avatar' => get_avatar_src($this, 'small'),
        ];
    }
}
