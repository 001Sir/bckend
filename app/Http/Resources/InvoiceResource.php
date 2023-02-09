<?php

namespace App\Http\Resources;

use App\Models\Package;
use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'partner' => Partner::find($this->partner_id)->company_name,
            'package' => Package::find($this->package_id)->name,
            'invoiced_date' => $this->invoiced_date,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'paid' => $this->is_paid,
            'payment_method' => $this->payment_method,
            'body' => $this->desc,
            'amount_paid' => $this->amount_paid,
            'date_paid' => $this->date_paid
        ];
    }
}
