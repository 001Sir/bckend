<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\JobApplicationRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;

interface JobApplicationRepositoryInterface
{
    public function index(Job $job);
    public function store(JobApplicationRequest $request);
    public function destroy(JobApplication $jobApplication);
    public function shortlistApplicant(JobApplication $jobApplication);
    public function listJobApplicationUser(User $user);
    public function listShortlistedJobsUser(User $user);
    public function countUserShortlistedJobs(User $user);
}