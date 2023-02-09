<?php

namespace App\Repository\API\V1;

use App\Http\Requests\InvoicePaidRequest;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\InvoiceRepositoryInterface;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Partner;
use App\Utils\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    use Response;

    /**
     * Retrieve all invoices
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $invoices = Invoice::paginate(3);

            return InvoiceResource::collection($invoices);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Create invoice for partner
     * 
     * @param Package $package
     * @param Partner $partner
     * @return InvoiceResource
     */
    public function createInvoice(InvoiceRequest $request)
    {
        try {
            $invoice = Invoice::create($request->only(['partner_id', 'package_id', 'invoiced_date', 'due_date', 'amount', 'is_paid']));

            return new InvoiceResource($invoice);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with creating an invoice, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Mark an invoice as paid
     * 
     * @param mixed $invoicePaid
     * @return JSONResponse
     */
    public function invoicePaid($invoicePaid)
    {
        try {
            $validator = Validator::make([
                'invoice_id' => $invoicePaid['invoice_id'],
                'is_paid' => $invoicePaid['is_paid'],
                'payment_method' => $invoicePaid['payment_method'],
                'desc' => $invoicePaid['desc'],
                'amount_paid' => $invoicePaid['amount_paid'],
                'date_paid' => $invoicePaid['date_paid']
            ], [
                'invoice_id' => ['exists:invoices,id', 'required'],
                'is_paid' => ['boolean', 'required'],
                'payment_method' => ['string', 'required'],
                'desc' => ['string', 'required'],
                'amount_paid' => ['regex:/^\d*(\.\d{2})?$/', 'required'],
                'date_paid' => ['date', 'required']
            ]);

            if ($validator->passes()) {
                $invoice = Invoice::find($invoicePaid['invoice_id']);
                $invoice->update([
                    'is_paid' => $invoicePaid['is_paid'],
                    'payment_method' => $invoicePaid['payment_method'],
                    'desc' => $invoicePaid['desc'],
                    'amount_paid' => $invoicePaid['amount_paid'],
                    'date_paid' => $invoicePaid['date_paid']
                ]);
                $package = Package::find($invoice->package_id);
                $partner = Partner::find($invoice->partner_id);

                $valid_date = Carbon::now();

                switch ($package->subscription_type) {
                    case 'monthly':
                        $valid_date->addMonth();
                        break;
                    case 'annual':
                        $valid_date->addYear();
                        break;
                    default:
                        break;
                }

                $partner->package()->syncWithPivotValues([$package->id], ['valid_until' => $valid_date]);

                return new InvoiceResource($invoice);
            }
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating the invoice, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Paid Invoice Stats of 3 packages for last six months
     * 
     * @return JSONResponse
     */
    public function paidInvoiceStats(): JsonResponse
    {
        try {
            $noOfMonths = 6;
            $packages = Package::all()->where('price', '>', 0)->take(3);
            $statsCount = Array();

            foreach ($packages as $package) {
                $statsCount[$package->name] = array();

                for ($i=0; $i<=$noOfMonths; $i++) {
                    $dateKey = date('M, y',strtotime('-'.$i.' month'));
                    $statsCount[$package->name][$dateKey] = round(Invoice::where('package_id', $package->id)->whereMonth('date_paid', date('m',strtotime('-'.$i.' month')))->where('is_paid', true)->sum('amount_paid'), 2);
                }
            }            
            
            return $this->responseData($statsCount);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("We're having trouble retrieving invoice stats."), "exception" => $th], 500);
        }
    }

    /**
     * List all invoices of partners
     * 
     * @param mixed $id
     * @return JSONResponse
     */
    public function partnerInvoices($id)
    {
        try {
            $invoices = Invoice::where('partner_id', $id)->get();

            return InvoiceResource::collection($invoices);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }
}
