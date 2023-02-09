<?php
namespace App\Interfaces\API\V1\Payments;

use App\Http\Requests\Payments\InvoiceCardPaymentRequest;

interface PaymentRepositoryInterface
{
    public function partnerCardInvoicePayment(InvoiceCardPaymentRequest $invoicePayment);
}
