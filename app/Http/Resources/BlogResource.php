<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->when($request->is('api/blogs'), $this->excerpt),
            'featured_image' => get_storage_file_url(optional($this->image)->path, 'full'),
            'content' => $this->when($request->is('api/blog/*'), $this->content),
            'published_at' => date('F j, Y', strtotime($this->published_at)),
            'views' => $this->views,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'comments_count' => $this->comments_count,
            'updated_at' => $this->updated_at->diffForHumans(),
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->getName(),
                'avatar' => get_avatar_src($this->author, 'small'),
            ],
            'tags' => $this->when($request->is('api/blog/*'), $this->tags->pluck('name')->toArray()),
            'comments' => $this->when($request->is('api/blog/*'), BlogCommentResource::collection($this->publishedComments)),
        ];
    }
}
