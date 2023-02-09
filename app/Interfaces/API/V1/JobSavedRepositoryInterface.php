<?php
namespace App\Interfaces\API\V1;

use App\Http\Requests\JobSavedRequest;
use App\Models\JobSaved;
use App\Models\User;

interface JobSavedRepositoryInterface
{
    public function index(User $user);
    public function store(JobSavedRequest $request);
    public function destroy(JobSaved $jobApplication);
}