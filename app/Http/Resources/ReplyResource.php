<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'reply' => $this->reply,
            'user' => $this->when($this->user_id, new UserResource($this->user)),
            'customer' => $this->when($this->customer_id, new CustomerLightResource($this->customer)),
            'read' => $this->read,
            'updated_at' => $this->updated_at->diffForHumans(),
            'attachments' => $this->when($this->attachments, AttachmentResource::collection($this->attachments)),
        ];
    }
}
