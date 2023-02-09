<?php
namespace App\Repository\API\V1;

use App\Http\Requests\FeatureRequest;
use App\Http\Resources\FeatureResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\FeatureRepositoryInterface;
use App\Models\Feature;
use App\Utils\Response;

class FeatureRepository implements FeatureRepositoryInterface
{
    use Response;

    /**
     * Retrieve all features
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $features = Feature::with('packages')->get();

            return FeatureResource::collection($features);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new feature
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(FeatureRequest $request)
    {
        try {
            $feature = Feature::create($request->only(['config_name', 'name']));

            return new FeatureResource($feature);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your feature, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing feature
     * @param mixed $request
     * @param mixed $feature
     * @return JSONResponse
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        try {
            $feature->update($request->only(['config_name', 'name']));

            return new FeatureResource($feature);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your feature, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete feature from database
     * @param mixed $feature
     * @return JSONResponse
     */
    public function destroy(Feature $feature)
    {
        try {
            if($feature->display_only == false) {
                return $this->responseError(__('This feature is written in the backend, hence it cannot be removed.', 500));
            }
            $feature->delete();

            return $this->responseData(null, __('Feature deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Feature deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
