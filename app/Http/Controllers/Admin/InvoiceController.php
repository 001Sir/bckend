<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\API\V1\InvoiceRepository;

class InvoiceController extends Controller
{
    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function index()
    {
        return $this->invoiceRepository->index();
    }

    public function paidInvoiceStats()
    {
        return $this->invoiceRepository->paidInvoiceStats();
    }

    public function partnerInvoices($id)
    {
        return $this->invoiceRepository->partnerInvoices($id);
    }
}
