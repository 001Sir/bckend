<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Category;
use App\Models\Job;
use App\Models\Partner;
use App\Repository\API\V1\JobRepository;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private JobRepository $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function index()
    {
        return $this->jobRepository->index();
    }

    public function store(JobRequest $request)
    {
        return $this->jobRepository->store($request);
    }

    public function show($id)
    {
        return $this->jobRepository->show($id);
    }

    public function update(JobRequest $request, $id)
    {        
        $job = Job::find($id);
        return $this->jobRepository->update($request, $job);
    }

    public function destroy(Job $job)
    {
        return $this->jobRepository->destroy($job);
    }

    public function listJobsPartner(Request $request)
    {
        $partner = Partner::find($request->partner_id);
        return $this->jobRepository->partnerJobs($partner);
    }

    public function listJobsCategory(Request $request)
    {
        $category = Category::find($request->category_id);
        return $this->jobRepository->categoryJobs($category);
    }

    public function listJobsApplicants($id)
    {
        return $this->jobRepository->retrieveJobApplicants($id);
    }

    public function stats()
    {
        return $this->jobRepository->jobStats();
    }
}
