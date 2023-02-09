<?php
namespace App\Interfaces\API\V1\Payments;

use App\Models\Invoice;

interface StripePaymentRepositoryInterface
{
    public function payWithCardStripe($stripe, Invoice $invoice);
}
