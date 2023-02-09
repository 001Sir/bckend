<?php

namespace App\Repository\API\V1;

use App\Http\Requests\JobSavedRequest;
use App\Http\Resources\JobSavedResource;
use App\Interfaces\API\V1\JobSavedRepositoryInterface;
use App\Models\JobSaved;
use App\Models\User;
use App\Utils\Response;

class JobSavedRepository implements JobSavedRepositoryInterface
{
    use Response;

    /**
     * Retrieve all jobs saved
     * @return JSONResponse
     */
    public function index(User $user)
    {
        try {
            $jobsSaved = JobSaved::with(['job','user'])->where('user_id', $user->id)->paginate(10);

            return JobSavedResource::collection($jobsSaved);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with fetching the data, try again."), "exception" => $th], 500);
        }
    }

    /**
     * Store a new saved job
     * @param JobSavedRequest $request
     * @return JSONResponse
     */
    public function store(JobSavedRequest $request)
    {
        try {
            $applicationExists = JobSaved::where('job_id', $request->job_id)->where('user_id', $request->user_id)->count();
            if($applicationExists) {
                return $this->responseError(__("You've already saved this job"), 409);
            }
            $jobApplication = JobSaved::create($request->only([
                'job_id',
                'user_id',
                'saved_time',
            ]));

            return new JobSavedResource($jobApplication);
        } catch (\Throwable $th) {
            return $this->responseError(["msg" => __("There was a problem with storing your job save, please check and try again."), "exception" => $th], 500);
        }
    }

    /**
     * Delete job application from database
     * @param JobSaved $jobSaved
     * @return JSONResponse
     */
    public function destroy(JobSaved $jobSaved)
    {
        try {
            $jobSaved->delete();

            return $this->responseData(null, __('Job removed from saved list.'));
        } catch (\Throwable $th) {
            return $this->responseError(__("Job Saved removal failed, try again."), 500);
        }
    }
}
