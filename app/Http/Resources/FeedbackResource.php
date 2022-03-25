<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'approved' => $this->approved,
            'spam' => $this->spam,
            'updated_at' => $this->updated_at->diffForHumans(),
            'labels' => $this->when($request->is('api/listing/*'), $this->getLabels()),
            'customer' => $this->when($request->is('api/listing/*'), [
                'id' => $this->customer->id,
                'name' => $this->customer->getName(),
                'avatar' => get_avatar_src($this->customer, 'tiny'),
            ]),
        ];
    }
}
