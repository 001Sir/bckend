<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'partner_id' => ['exists:partners,id', 'required'],
            'job_title' => ['string', 'required'],
            'is_remote' => ['boolean', 'required'],
            'category_id' => ['exists:categories,id', 'required'],
            'city' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'no_pay_range' => ['boolean', 'required'],
            'min_salary_range' => ['nullable', 'integer'],
            'max_salary_range' => ['nullable', 'integer'],
            'job_type_id' => ['exists:job_types,id', 'required'],
            'desc' => ['string', 'max:500', 'required'],
            'skills' => ['nullable'],
            'job_active_duration' => ['exists:posts_durations,id', 'nullable'],
            'is_published' => ['boolean', 'required'],
            'expiration_date' => ['nullable', 'date'],
            'status' => ['string', 'required']
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
            'partner_id.exists' => 'Entered company doesn\'t exist, please check again.',
            'category_id.exists' => 'Category doesn\'t exist, please check again.',
            'job_type_id.exists' => 'Job Type doesn\'t exist, please check your input.',
            'job_active_duration.exists' => 'Duration Type doesn\'t exist, please check your input.',
        ];
    }
}
