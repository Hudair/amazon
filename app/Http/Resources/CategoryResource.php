<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            // 'featured' => $this->featured,
            'description' => $this->when($this->description, $this->description),
            'category_sub_group_id' => $this->when($this->category_sub_group_id, $this->category_sub_group_id),
            'feature_image' => $this->when($this->featureImage, get_storage_file_url(optional($this->featureImage)->path, 'medium')),
            'cover_image' => $this->when($this->coverImage, get_cover_img_src($this, 'category')),
        ];
    }
}
