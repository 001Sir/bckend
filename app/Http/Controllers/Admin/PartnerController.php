<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Repository\Partner\PartnerRepository;

class PartnerController extends Controller
{
    private PartnerRepository $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    public function index()
    {
        return $this->partnerRepository->index();
    }

    public function store(PartnerRequest $request)
    {
        return $this->partnerRepository->store($request);
    }

    public function update(PartnerRequest $request, $id)
    {        
        $partner = Partner::find($id);
        return $this->partnerRepository->update($request, $partner);
    }

    public function destroy(Partner $partner)
    {
        return $this->partnerRepository->destroy($partner);
    }
}
