<?php

namespace App\Http\Resources;

use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
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
            'resume' => new ResumeResource(Resume::find($this->resume_id)),
            'applied_time' => $this->applied_time,
            'shortlisted' => $this->is_shortlisted,
            'shortlisted_time' => $this->selected_time,
            'read' => $this->is_read,
            'read_time' => $this->read_receipt_time
        ];
    }
}
