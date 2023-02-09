<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $created_at = date('M j-g:i a', strtotime($this->created_at));
        $startDate = date('M j-g:i a', strtotime($this->startDate));
        $endDate = date('M j-g:i a', strtotime($this->endDate));

        $res = [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'location' => $this->location,
            'duration' => $startDate.' - '.$endDate,
            'workers' => $this->workers,
            'bookNumber' => $this->bookedMen == '' ? 0 : count(explode(',', $this->bookedMen)) - 2,
            'reserveNumber' => $this->reservedMen == '' ? 0 : count(explode(',', $this->reservedMen)) - 2,
            'payRate' => $this->payRate,
            'hours' => $this->hours,
            'fixedPay' => $this->fixedPay,
            'desc' => $this->desc,
            'postedTime' => $created_at,
            'status' => $this->status
        ];

        if ($this->company != null) {
            $res['company'] = $this->company;
            $res['rating'] = $this->rating;
        }

        return $res;
    }
}