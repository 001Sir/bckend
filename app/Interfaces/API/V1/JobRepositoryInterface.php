<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use App\Models\Partner;
use App\Models\User;

interface JobRepositoryInterface
{
    public function index();
    public function store(JobRequest $request);
    public function show($id);
    public function retrieveJobUser($id, User $user);
    public function retrieveJobApplicants($id);
    public function update(JobRequest $request, Job $job);
    public function destroy(Job $job);
    public function partnerJobs(Partner $partner);
    public function jobStats();
}