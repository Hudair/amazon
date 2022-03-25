<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryBoyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'first_name'    => $this->first_name,
            'last_name'    => $this->last_name,
            'nice_name'    => $this->nice_name,
            'phone_number' => $this->phone_number,
            'email'        => $this->email,
            'sex'          => $this->sex ? trans($this->sex) : null,
            'dob'          => $this->dob ? $this->dob : null,
            // 'description' => $this->description,
            'status'       => $this->status == true ? 'Active' : 'Inactive',
            'active'       => $this->status,
            'member_since' => optional($this->created_at)->diffForHumans(),
            'avatar'       => get_avatar_src($this, 'small'),
            'api_token'    => $this->when(isset($this->api_token), $this->api_token)
        ];
    }
}
