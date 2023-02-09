<?php

namespace App\Http\Resources;

use App\Models\JobType;
use App\Models\Partner;
use App\Models\PostDuration;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'partner' => new PartnerResource(Partner::find($this->partner_id)),
            'title' => $this->job_title,
            'remote_position' => $this->is_remote,
            'category' => new CategoryResource($this->category),
            'city' => $this->city,
            'country' => $this->country,
            'no_pay_range' => $this->no_pay_range,
            'min_salary_range' => $this->min_salary_range,
            'max_salary_range' => $this->max_salary_range,
            'job_type' => new JobTypeResource(JobType::find($this->job_type_id)),
            'desc' => $this->desc,
            'skills' => $this->skills,
            'job_active_duration' => new PostDurationResource(PostDuration::find($this->job_active_duration)),
            'published' => $this->is_published,
            'expiration' => $this->expiration_date,
            'status' => $this->status
        ];
    }
}
