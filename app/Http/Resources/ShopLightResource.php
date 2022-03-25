<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopLightResource extends JsonResource
{
    /**
     * @var feedback_given
     */
    private $feedback_id;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $feedback_id = null)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->resource = $resource;
        $this->feedback_id = $feedback_id;
    }

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
            'slug' => $this->slug,
            'verified' => $this->isVerified(),
            'verified_text' => $this->verifiedText(),
            'image' => get_logo_url($this, 'small'),
            'contact_number' => $this->config->support_phone,
            // 'can_evaluate' => $this->when($request->is('api/order/*'), ! (bool) $this->feedback_id),
            'rating' => $this->rating(),
            'feedbacks_count' => $this->rating() ? $this->avgFeedback->count : 0,
            'feedbacks' => $this->when($request->is('api/order/*'), function () {
                $feedback = \App\Models\Feedback::find($this->feedback_id);

                return $feedback ? new FeedbackResource($feedback) : null;
            }),

            // 'feedback' => $this->when($request->is('api/order/*'), function () {
            //     $feedback = \App\Models\Feedback::find($this->feedback_id);

            //     return $feedback ? new FeedbackResource($feedback) : null;
            // }),
        ];
    }
}
