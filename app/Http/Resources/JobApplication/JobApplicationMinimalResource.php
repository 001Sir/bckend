<?php

namespace App\Http\Resources\JobApplication;

use App\Http\Resources\ResumeResource;
use App\Http\Resources\User\UserMinimalResource;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationMinimalResource extends JsonResource
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
            'user' => new UserMinimalResource(User::find($this->user_id)),
            'resume' => new ResumeResource(Resume::find($this->resume_id)),
            'shortlisted' => $this->is_shortlisted,
            'read' => $this->is_read,
        ];
    }
}
