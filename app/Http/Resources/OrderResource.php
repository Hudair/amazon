<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer_phone_number' => $this->customer->address->phone ?? $this->customer_phone_number,
            'ip_address' => $this->ip_address,
            'email' => $this->email,
            // 'disputed' => $this->dispute()->exists(),
            'dispute_id' => optional($this->dispute)->id,
            'order_status' => $this->orderStatus(true),
            'payment_status' => $this->paymentStatusName(true),
            'payment_method' => new PaymentMethodResource($this->paymentMethod),
            'message_to_customer' => $this->message_to_customer,
            'buyer_note' => $this->buyer_note,
            'ship_to' => $this->ship_to,
            'shipping_zone_id' => $this->shipping_zone_id,
            'shipping_rate_id' => $this->shipping_rate_id,
            'shipping_address' => strip_tags(str_replace('<br/>', ', ', $this->shipping_address)),
            'billing_address' => strip_tags(str_replace('<br/>', ', ', $this->billing_address)),
            'shipping_weight' => get_formated_weight($this->shipping_weight),
            'packaging_id' => $this->packaging_id,
            'coupon_id' => $this->coupon_id,
            'total' => get_formated_currency($this->total, config('system_settings.decimals', 2)),
            'shipping' => get_formated_currency($this->shipping, config('system_settings.decimals', 2)),
            'packaging' => $this->packaging ? get_formated_currency($this->packaging, config('system_settings.decimals', 2)) : null,
            'handling' => $this->handling ? get_formated_currency($this->handling, config('system_settings.decimals', 2)) : null,
            'taxes' => $this->taxes ? get_formated_currency($this->taxes, config('system_settings.decimals', 2)) : null,
            'discount' => $this->discount ? get_formated_currency($this->discount, config('system_settings.decimals', 2)) : null,
            'grand_total' => get_formated_currency($this->grand_total, config('system_settings.decimals', 2)),
            'taxrate' => $this->taxrate,
            'order_date' => date('F j, Y', strtotime($this->created_at)),
            'shipping_date' => $this->shipping_date ? date('F j, Y', strtotime($this->shipping_date)) : null,
            'delivery_date' => $this->delivery_date ? date('F j, Y', strtotime($this->delivery_date)) : null,
            'goods_received' => $this->goods_received,
            // 'feedback_given' => (bool) $this->feedback_id,
            'can_evaluate' => $this->canEvaluate(),
            'tracking_id' => $this->tracking_id,
            'tracking_url' => $this->getTrackingUrl(),
            'customer' => $this->customer_id ? new CustomerLightResource($this->customer) : null,
            'shop' => new ShopLightResource($this->shop, $this->feedback_id),
            'items' => OrderItemResource::collection($this->inventories),
            'conversation' => $this->conversation,
        ];
    }
}
