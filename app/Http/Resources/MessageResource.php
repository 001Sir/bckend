<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $res = [
            'id' => $this->id,
            'msg' => $this->msg,
            'date' => $this->created_at,
            'sender' => $this->sender,
            'partnerId' => $this->partnerId,
            'workerId' => $this->workerId,
        ];

        if ($this->name != null)
            $res['name'] = $this->name;
        return $res;
    }
}