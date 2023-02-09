<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostCompleteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->post_title,
            'slug' => $this->slug,
            'body' => $this->body,
            'featured_image' => $this->featured_image,
            'author' => new AdminResource(Admin::find($this->author_id)),
            'published' => $this->is_published,
            'published_date' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'modified_date' => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s'),
            'excerpt' => $this->excerpt,
        ];
    }
}
