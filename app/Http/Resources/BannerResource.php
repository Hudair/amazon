<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'bg_image' => $this->when($this->bannerbg, get_storage_file_url(optional($this->bannerbg)->path, null)),
            'image' => $this->when($this->featureImage, get_storage_file_url(optional($this->featureImage)->path, null)),
            'link' => url($this->link),
            'link_label' => $this->link_label,
            'bg_color' => $this->bg_color,
            'group_id' => $this->group_id,
        ];
    }
}
