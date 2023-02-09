<?php

namespace App\Repository\API\V1\Payments;

use App\Http\Requests\Payments\InvoiceCardPaymentRequest;
use App\Interfaces\API\V1\Payments\PaymentRepositoryInterface;
use App\Models\Invoice;
use App\Repository\API\V1\InvoiceRepository;
use App\Utils\Response;
use Carbon\Carbon;

class PaymentRepository implements PaymentRepositoryInterface
{
    use Response;

    private StripePaymentRepository $stripeRepository;
    private InvoiceRepository $invoiceRepository;

    function __construct()
    {
        $this->stripeRepository = new StripePaymentRepository;
        $this->invoiceRepository = new InvoiceRepository;
    }

    /**
     * Process payment for the partner invoice
     * @param InvoiceCardPaymentRequest $invoicePayment
     * @return JSONResponse
     */
    public function partnerCardInvoicePayment(InvoiceCardPaymentRequest $invoicePayment)
    {
        try {
            $invoice = Invoice::find($invoicePayment->invoice_id);
            $payment_method = $invoicePayment->payment_method;
            $res = '';

            if($invoice->is_paid) {
                return $this->responseError(__('Invoice has already been settled.'));
            }

            switch ($payment_method) {
                case 'stripe': // Handle Stripe payments
                    $stripePayment = $invoicePayment;
                    unset($stripePayment['invoice_id']);
                    unset($stripePayment['payment_method']);

                    $res = $this->stripeRepository->payWithCardStripe($stripePayment, $invoice);
                    break;
                default:
                    break;
            }

            if($res['status'] == 'success') {
                $invoicePaymentReq = [
                    'invoice_id' => $invoice->id,
                    'is_paid' => true,
                    'payment_method' => $payment_method,
                    'desc' => 'Payment for Package - '.$invoice->package->name,
                    'amount_paid' => $invoice->amount,
                    'date_paid' => Carbon::now()
                ];
                $res = $this->invoiceRepository->invoicePaid($invoicePaymentReq);
            }

            return $this->responseData($res, __('Card Payment was processed.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => "There was a problem processing the payment, please try again.", "exception" => $th], 500);
        }
    }
}
