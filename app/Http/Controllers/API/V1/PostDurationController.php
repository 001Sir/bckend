<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Repository\API\V1\PostDurationRepository;

class PostDurationController extends Controller
{
    private PostDurationRepository $postdurationRepository;

    public function __construct(PostDurationRepository $postdurationRepository)
    {
        $this->postdurationRepository = $postdurationRepository;
    }

    public function index()
    {
        return $this->postdurationRepository->index();
    }

    public function listForPartners($id)
    {
        return $this->postdurationRepository->listForPartners($id);
    }
}
