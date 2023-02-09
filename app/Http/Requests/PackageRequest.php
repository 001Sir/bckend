<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'name' => ['string', 'max:200', 'required'],
            'price' => ['regex:/^\d*(\.\d{1,2})?$/', 'required'], // Regex. checks if its a float or number
            'subscription_type' => ['string', 'required'],
            'is_active' => ['boolean', 'required']
        ];
    }
}
