<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'img_url' => ['string', 'required'],
            'company_name' => ['string', 'max:200', 'required'],
            'short_name' => ['string', 'max:200', 'required'],
            'email' => ['email', 'unique:partners', 'required'],
            'password' => ['string', 'confirmed', 'required'],
            'bio' => ['string', 'required'],
            'employee_count' => ['string', 'required'],
        ];
    }
}
