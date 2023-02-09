<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Repository\API\V1\JobApplicationRepository;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    private JobApplicationRepository $jobApplicationRepository;

    public function __construct(JobApplicationRepository $jobApplicationRepository)
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    public function index(Request $request)
    {
        $job = Job::find($request->job_id);
        return $this->jobApplicationRepository->index($job);
    }

    public function store(JobApplicationRequest $request)
    {
        return $this->jobApplicationRepository->store($request);
    }

    public function destroy(JobApplication $jobApplication)
    {
        return $this->jobApplicationRepository->destroy($jobApplication);
    }

    public function shortlistApplicant(Request $request)
    {
        $jobApplication = JobApplication::find($request->id);
        return $this->jobApplicationRepository->shortlistApplicant($jobApplication);
    }

    public function listApplications(Request $request)
    {
        $user = User::find($request->user_id);
        return $this->jobApplicationRepository->listJobApplicationUser($user);
    }

    public function listShortlistedJobs(Request $request)
    {
        $user = User::find($request->user_id);
        return $this->jobApplicationRepository->listShortlistedJobsUser($user);
    }

    public function shortlistedCount(Request $request)
    {
        $user = User::find($request->user_id);
        return $this->jobApplicationRepository->countUserShortlistedJobs($user);
    }
    
    public function toggleReadStatus($id)
    {
        $jobApplication = JobApplication::find($id);
        return $this->jobApplicationRepository->toggleReadStatus($jobApplication);
    }
}
