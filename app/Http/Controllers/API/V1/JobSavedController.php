<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobSavedRequest;
use App\Models\JobSaved;
use App\Models\User;
use App\Repository\API\V1\JobSavedRepository;
use App\Utils\Response;

class JobSavedController extends Controller
{
    use Response;

    private JobSavedRepository $jobSavedRepository;

    public function __construct(JobSavedRepository $jobSavedRepository)
    {
        $this->jobSavedRepository = $jobSavedRepository;
    }

    public function show($id)
    {
        $user = User::find($id);
        return $this->jobSavedRepository->index($user);
    }

    public function store(JobSavedRequest $request)
    {
        return $this->jobSavedRepository->store($request);
    }

    public function destroy($user, $id)
    {
        $jobSavedExists = JobSaved::where('user_id', $user)->where('job_id', $id)->exists();
        if($jobSavedExists) {
            $jobSaved = JobSaved::where('user_id', $user)->where('job_id', $id)->first();
            return $this->jobSavedRepository->destroy($jobSaved);
        } else {
            return $this->responseError('Job not saved');
        }
    }
}
