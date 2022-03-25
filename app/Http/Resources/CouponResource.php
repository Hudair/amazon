<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code' => $this->code,
            'amount' => $this->getFormatedAmountText(),
            'valid' => $this->isLive(),
            'validity' => $this->validityText(true),
            // 'starting_time' => $this->starting_time->format('M j,Y g:i a'),
            // 'ending_time' => $this->ending_time->format('M j,Y g:i a'),
            'shop' => new ShopLightResource($this->shop),
        ];
    }
}
