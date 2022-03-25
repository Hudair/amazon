<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'dob' => $this->dob ? $this->dob : null,
            // 'dob' => $this->dob ? date('F j, Y', strtotime($this->dob)) : null,
            'sex' => $this->sex ? trans($this->sex) : null,
            'description' => $this->description,
            'active' => $this->active,
            'email' => $this->email,
            'accepts_marketing' => $this->accepts_marketing,
            'member_since' => optional($this->created_at)->diffForHumans(),
            'avatar' => get_avatar_src($this, 'small'),
            // 'last_visited_at' => $this->last_visited_at,
            // 'last_visited_from' => $this->last_visited_from,
            'api_token' => $this->when(isset($this->api_token), $this->api_token),
        ];
    }
}
