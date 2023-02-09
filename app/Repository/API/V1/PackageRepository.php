<?php
namespace App\Repository\API\V1;

use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\PackageRepositoryInterface;
use App\Models\Package;
use App\Utils\Response;

class PackageRepository implements PackageRepositoryInterface
{
    use Response;

    /**
     * Retrieve all packages
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $packages = Package::with('features')->get();

            return PackageResource::collection($packages);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new packages
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(PackageRequest $request)
    {
        try {
            $package = Package::create($request->only(['name', 'price', 'subscription_type', 'is_active']));
            if($request->features) {
                $features = explode(',', $request->features);
                $package->features()->sync($features);
            }

            return new PackageResource($package);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your package, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing package
     * @param mixed $request
     * @param mixed $package
     * @return JSONResponse
     */
    public function update(PackageRequest $request, Package $package)
    {
        try {
            if($request->features) {
                $package->features()->sync($request->features);
            }
            $package->update($request->only(['name', 'price', 'subscription_type', 'is_active']));

            return new PackageResource($package);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your package, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete package from database
     * @param mixed $package
     * @return JSONResponse
     */
    public function destroy(Package $package)
    {
        try {
            $package->delete();

            return $this->responseData(null, __('Package deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Package deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
