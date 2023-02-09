<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobTypeRequest;
use App\Models\JobType;
use App\Repository\API\V1\JobTypeRepository;

class JobTypeController extends Controller
{
    private JobTypeRepository $jobtypeRepository;

    public function __construct(JobTypeRepository $jobtypeRepository)
    {
        $this->jobtypeRepository = $jobtypeRepository;
    }

    public function index()
    {
        return $this->jobtypeRepository->index();
    }

    public function store(JobTypeRequest $request)
    {
        return $this->jobtypeRepository->store($request);
    }

    public function update(JobTypeRequest $request, $id)
    {
        $jobtype = JobType::find($id);
        return $this->jobtypeRepository->update($request, $jobtype);
    }

    public function destroy(JobType $jobType)
    {
        return $this->jobtypeRepository->destroy($jobType);
    }
}
