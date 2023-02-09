<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartnerDashboardResource extends JsonResource
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
            'profile_picture' => $this->img_url,
            'name' => $this->company_name,
            'display_name' => $this->short_name,
            'email' => $this->email,
            'bio' => $this->bio,
            'company_size' => $this->employee_count,
            'member_since' => $this->created_at,
            'posted_jobs' => $this->jobs_count
        ];
    }
}
