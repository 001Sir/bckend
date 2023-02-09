<?php

namespace App\Repository\API\V1;

use App\Http\Resources\PostDurationResource;
use App\Http\Requests\PostDurationRequest;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\PostDurationRepositoryInterface;
use App\Models\Package;
use App\Models\Partner;
use App\Models\PostDuration;
use App\Utils\Response;

class PostDurationRepository implements PostDurationRepositoryInterface
{
    use Response;

    /**
     * Retrieve all post durations
     * 
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $postDurations = PostDuration::paginate(10);

            return PostDurationResource::collection($postDurations);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Retrieve all post durations for partner
     * 
     * @param mixed $id
     * @return JSONResponse
     */
    public function listForPartners($id)
    {
        try {
            $package = Partner::where('id', $id)->with('package')->get()->pluck('package');
            $packageLongPostDuration = Package::where('id', $package[0][0]->id)->whereHas('features', function ($query) {
                return $query->where('config_name', 'long-post-duration');
            })->exists();

            if($packageLongPostDuration) {
                $postDurations = PostDuration::all();
            } else {
                $postDurations = PostDuration::where('is_paid', false)->get();
            }

            return PostDurationResource::collection($postDurations);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => strval($th)], 500);
        }
    }

    /**
     * Save a new post duration
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(PostDurationRequest $request)
    {
        try {
            $postDuration = PostDuration::create($request->only(['name', 'duration', 'is_paid']));

            return new PostDurationResource($postDuration);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your post duration, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing post duration
     * @param mixed $request
     * @param mixed $postDuration
     * @return JSONResponse
     */
    public function update(PostDurationRequest $request, PostDuration $postDuration)
    {
        try {
            $postDuration->update($request->only(['name', 'duration', 'is_paid']));

            return new PostDurationResource($postDuration);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your post duration, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete post duration from database
     * @param mixed $postDuration
     * @return JSONResponse
     */
    public function destroy(PostDuration $postDuration)
    {
        try {
            $postDuration->delete();

            return $this->responseData(null, __('Post duration deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Post duration deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
