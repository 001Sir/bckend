<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\JobTypeRequest;
use App\Models\JobType;

interface JobTypeRepositoryInterface
{
    public function index();
    public function store(JobTypeRequest $request);
    public function update(JobTypeRequest $request, JobType $jobType);
    public function destroy(JobType $jobType);
}