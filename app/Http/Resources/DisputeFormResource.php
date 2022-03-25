<?php

namespace App\Http\Resources;

use App\Models\DisputeType;
use Illuminate\Http\Resources\Json\JsonResource;

class DisputeFormResource extends JsonResource
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
            'order_number' => $this->order_number,
            'order_status' => $this->orderStatus(true),
            'total' => get_formated_currency($this->total, config('system_settings.decimals', 2)),
            'grand_total' => get_formated_currency($this->grand_total, config('system_settings.decimals', 2)),
            'total_raw' => $this->total,
            'grand_total_raw' => $this->grand_total,
            'goods_received' => $this->goods_received,
            'items' => $this->inventories->pluck('title', 'id'),
            'dispute_type' => DisputeType::orderBy('id')->pluck('detail', 'id'),
        ];
    }
}
