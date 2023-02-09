<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\InvoiceRequest;

interface InvoiceRepositoryInterface
{
    public function index();
    public function createInvoice(InvoiceRequest $request);
    public function invoicePaid($invoicePaid);
    public function paidInvoiceStats();
}