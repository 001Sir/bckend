<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebPageRequest extends FormRequest
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
            'title' => ['string', 'max:200', 'required'],
            'slug' => ['string', 'max:150', 'unique:web_pages'],
            'body' => ['string', 'required'],
            'is_published' => ['boolean', 'required'],
            'status' => ['string', 'required']
        ];
    }
}
