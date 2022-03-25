<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerLightResource extends JsonResource
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
            'phone_number' => optional($this->address)->phone,
            'active' => $this->active,
            'avatar' => get_avatar_src($this, 'small'),
            'api_token' => $this->when(isset($this->api_token), $this->api_token),
        ];
    }
}
