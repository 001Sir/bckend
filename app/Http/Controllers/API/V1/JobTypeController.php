<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
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
}
