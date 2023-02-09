<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'package_id' => ['exists:packages,id', 'required'],
            'invoiced_date' => ['date', 'required'],
            'due_date' => ['date', 'required'],
            'amount' => ['regex:/^\d*(\.\d{2})?$/', 'required']
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
            'job_type_id.exists' => 'Job Type doesn\'t exist, please check your input.',
            'job_active_duration.exists' => 'Duration Type doesn\'t exist, please check your input.',
        ];
    }
}
