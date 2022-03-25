<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DisputeLightResource extends JsonResource
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
            'reason' => $this->dispute_type->detail,
            'closed' => $this->isClosed(),
            'goods_received' => $this->order_received,
            'return_goods' => $this->return_goods,
            'status' => $this->statusName(true),
            'updated_at' => $this->updated_at->diffForHumans(),
            'shop' => new ShopLightResource($this->shop),
            'order_details' => [
                'id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'order_status' => $this->order->orderStatus(true),
                'payment_status' => $this->order->paymentStatusName(true),
                'grand_total' => get_formated_currency($this->order->grand_total, config('system_settings.decimals', 2)),
                'grand_total_raw' => $this->order->grand_total,
                'order_date' => date('F j, Y', strtotime($this->order->created_at)),
                'items' => OrderItemResource::collection($this->order->inventories),
            ],
        ];
    }
}
