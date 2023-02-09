<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostRequest extends FormRequest
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
            'post_title' => ['string', 'required'],
            'featured_image' => ['url', 'required'],
            'author_id' => ['exists:admins,id', 'required'],
            'is_featured' => ['boolean', 'required'],
            'is_published' => ['boolean', 'required'],
            'excerpt' => ['string', 'max:200', 'required'],
            'body' => ['string', 'required']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'author_id.exists' => 'Author doesn\'t exist, please check again.'
        ];
    }
}
