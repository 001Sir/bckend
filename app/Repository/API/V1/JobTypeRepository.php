<?php
namespace App\Repository\API\V1;

use App\Http\Requests\JobTypeRequest;
use App\Http\Resources\JobTypeResource;
use Illuminate\Http\JsonResponse;

use App\Interfaces\API\V1\JobTypeRepositoryInterface;
use App\Models\JobType;
use App\Utils\Response;

class JobTypeRepository implements JobTypeRepositoryInterface
{
    use Response;

    /**
     * Retrieve all job types
     * @return JSONResponse
     */
    public function index()
    {
        try {
            $jobtypes = JobType::paginate(10);

            return JobTypeResource::collection($jobtypes);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Save a new job types
     * @param mixed $request
     * @return JSONResponse
     */
    public function store(JobTypeRequest $request)
    {
        try {
            $jobTypes = JobType::create($request->only(['name']));

            return new JobTypeResource($jobTypes);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with saving your job type, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Update an existing job type
     * @param mixed $request
     * @param mixed $jobType
     * @return JSONResponse
     */
    public function update(JobTypeRequest $request, JobType $jobType)
    {
        try {
            $jobType->update($request->only(['name']));

            return new JobTypeResource($jobType);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with updating your job type, please try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete job type from database
     * @param mixed $jobType
     * @return JSONResponse
     */
    public function destroy(JobType $jobType)
    {
        try {
            $jobType->delete();

            return $this->responseData(null, __('Job type deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("Job type deletion failed, try again."), "exception" => $th], 500);
        }
    }
}
