<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\PackageRequest;
use App\Models\Package;

interface PackageRepositoryInterface
{
    public function index();
    public function store(PackageRequest $request);
    public function update(PackageRequest $request, Package $package);
    public function destroy(Package $package);
}