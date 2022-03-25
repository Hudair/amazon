<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'shop_id' => (int) $this->shop_id,
            'user' => $this->when($this->user_id, new UserResource($this->user)),
            'customer' => $this->when($this->customer_id, new CustomerLightResource($this->customer)),
            'shop' => $this->when($this->shop_id, new ShopDryResource($this->shop)),
            'subject' => $this->subject,
            'message' => $this->message,
            'order_id' => $this->when($this->order_id, (int) $this->order_id),
            'item' => new ItemLightResource($this->item),
            'status' => $this->status,
            'label' => $this->label,
            'attachments' => $this->when($this->attachments, AttachmentResource::collection($this->attachments)),
            'replies' => ReplyResource::collection($this->whenLoaded('replies')),
            // 'replies' => ReplyResource::collection($this->replies),
        ];
    }
}
