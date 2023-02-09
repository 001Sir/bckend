<?php

namespace App\Http\Resources;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSavedResource extends JsonResource
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
            'job_post' => new JobResource(Job::find($this->job_id)),
            'user' => new UserResource(User::find($this->user_id)),
            'saved_time' => $this->saved_time,
        ];
    }
}
