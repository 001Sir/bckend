<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Repository\API\V1\PackageRepository;

class PackageController extends Controller
{
    private PackageRepository $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function index()
    {
        return $this->packageRepository->index();
    }

    public function store(PackageRequest $request)
    {
        return $this->packageRepository->store($request);
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::find($id);
        return $this->packageRepository->update($request, $package);
    }

    public function destroy(Package $package)
    {
        return $this->packageRepository->destroy($package);
    }
}
