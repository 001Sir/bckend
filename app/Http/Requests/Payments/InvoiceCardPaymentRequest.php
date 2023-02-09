<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCardPaymentRequest extends FormRequest
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
            'invoice_id' => ['exists:invoices,id', 'required'],
            'payment_method' => ['string', 'required'],
            'card_no' => ['nullable'],
            'cc_expiry_month' => ['nullable'],
            'cc_expiry_year' => ['nullable'],
            'cc_cvv_no' => ['nullable']
        ];
    }
}
