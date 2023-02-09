<?php

namespace App\Repository\API\V1\Payments;

use Exception;
use App\Interfaces\API\V1\Payments\StripePaymentRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;
use App\Utils\Response;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class StripePaymentRepository implements StripePaymentRepositoryInterface
{
    use Response;

    /**
     * Processes payment with stripe
     * @param mixed $cardInfo
     * @param InvoiceRequest $invoice
     * @return JSONResponse
     */
    public function payWithCardStripe($cardInfo, Invoice $invoice)
    {
        $validator = Validator::make([
            'card_no' => $cardInfo->card_no,
            'cc_expiry_month' => $cardInfo->cc_expiry_month,
            'cc_expiry_year' => $cardInfo->cc_expiry_year,
            'cc_cvv_no' => $cardInfo->cc_cvv_no
        ], [
            'card_no' => 'string|required',
            'cc_expiry_month' => 'required',
            'cc_expiry_year' => 'required',
            'cc_cvv_no' => 'required'
        ]);

        if ($validator->passes()) {
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $cardInfo->card_no,
                        'exp_month' => $cardInfo->cc_expiry_month,
                        'exp_year' => $cardInfo->cc_expiry_year,
                        'cvc' => $cardInfo->cc_cvv_no
                    ],
                ]);

                if(isset($token['id'])) {
                    $charge = $stripe->charges()->create([
                        'card' => $token['id'],
                        'currency' => 'USD',
                        'amount' => $invoice->amount,
                        'description' => env('APP_NAME').' INV. NO:'.$invoice->id.' PACKAGE:'.$invoice->package->name.'.',
                    ]);

                    if($charge['status'] == 'succeeded') {
                        return ['status' => 'success', 'data' => $charge];
                    }
                }
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                throw new Exception($e);
            }
        }
    }
}
