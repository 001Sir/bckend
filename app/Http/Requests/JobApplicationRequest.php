<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
            'job_id' => ['exists:jobs,id', 'required'],
            'user_id' => ['exists:users,id', 'required'],
            'resume_id' => ['exists:resumes,id', 'required'],
            'applied_time' => ['date', 'required'],
            'is_shortlisted' => ['boolean', 'required'],
            'selected_time' => ['date', 'nullable', 'required'],
            'is_read' => ['boolean', 'required'],
            'read_receipt_time' => ['date', 'nullable', 'required'],
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
            'job_id.exists' => 'Job doesn\'t exist, please check again.',
            'user_id.exists' => 'User doesn\'t exist, please check again.'
        ];
    }
}
