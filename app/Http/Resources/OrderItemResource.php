<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->pivot->item_description,
            'quantity' => $this->pivot->quantity,
            'unit_price' => get_formated_currency($this->pivot->unit_price, config('system_settings.decimals', 2)),
            'total' => get_formated_currency($this->pivot->unit_price * $this->pivot->quantity, config('system_settings.decimals', 2)),
            'image' => get_inventory_img_src($this, 'small'),
            'feedback' => $this->when($request->is('api/order/*'), function () {
                $feedback = \App\Models\Feedback::find($this->pivot->feedback_id);

                return $feedback ? new FeedbackResource($feedback) : null;
            }),
        ];
    }
}
