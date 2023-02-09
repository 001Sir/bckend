<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeRequest;
use App\Models\Resume;
use App\Repository\API\V1\ResumeRepository;

class ResumeController extends Controller
{
    private ResumeRepository $resumeRepository;

    public function __construct(ResumeRepository $resumeRepository)
    {
        $this->resumeRepository = $resumeRepository;
    }

    public function store(ResumeRequest $request)
    {
        return $this->resumeRepository->store($request);
    }

    public function destroy($id)
    {
        $resume = Resume::find($id);
        return $this->resumeRepository->destroy($resume);
    }

    public function userResumes($id)
    {
        return $this->resumeRepository->userResumes($id);
    }
}
