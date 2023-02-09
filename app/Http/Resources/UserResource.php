<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $isReturningToken = false;
    public static $wrap = null;
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = array();

        if ($this->isWorker) {
            $result = [
                'id' => $this->id,
                'email' => $this->email,
                'phoneNumber' => $this->phoneNumber,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'birthyear' => $this->birthyear,
                'address' => $this->address,
                'headline' => $this->headline,
                'result' => 'success'
            ];
        }
        else {
            $result = [
                'id' => $this->id,
                'email' => $this->email,
                'phoneNumber' => $this->phoneNumber,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'birthyear' => $this->birthyear,
                'address' => $this->address,
                'companyName' => $this->companyName,
                'EIN' => $this->EIN,
                'companyLocation' => $this->companyLocation,
                'rating' => $this->rating,
                'result' => 'success'
            ];
        }

        if ($this->isReturningToken)
            $result['token'] = $this->token;

        return $result;
    }
}